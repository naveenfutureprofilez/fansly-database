<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Http\Requests\ProSubscribeRequest;
use App\Http\Requests\TopupRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\Transaction;
use App\Models\CreatorPlan;
use App\Models\ProSubscription;
use App\Models\Subscription;
use App\Stripe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class For Manage Payment Related actions
 * For Front End users
 */
class PaymentsController extends Controller
{
    public function createPaymentMethod(CardRequest $cardRequest){
        $user = Auth::user();
        $methods = PaymentMethod::where('user_id', $user->id)->count();
        $reqData = $cardRequest->only([
            'meta',
            'card',
            'month',
            'year',
            'cvc',
        ]);
        $isDefault = $methods ? false : true;
        $pm = new PaymentMethod;
        $pm->user_id = $user->id;
        $pm->gateway = 'stripe';
        $pm->p_meta = $reqData['meta'];
        $pm->is_default = $isDefault;

        $stripe = new Stripe;
        $stripeResp = $stripe->createPaymentMethod([
            'number' => $reqData['card'],
            'exp_month' => $reqData['month'],
            'exp_year' => $reqData['year'],
            'cvc' => $reqData['cvc'],
        ]);
        if($stripeResp['status']){
            $card = $stripeResp['card'];
            $stripeResp = $stripe->attachPaymentMethod($user->stripe_id, $card->id, $isDefault);
            if($stripeResp['status']){
                $pm->p_key = $card->id;
                $pm->save();
                $resp = [
                    'status' => true,
                    'msg'    => 'Card added successfully',
                    'card'   => encrypt($pm->id)
                ];
            } else {
                $resp = $stripeResp;
            }
        } else {
            $resp = $stripeResp;
        }

        return response()->json($resp, 200);
    }


    /**
     * List All Payment Methods
     * For logged in user
     */
    public function listPaymentMethods(){
        $user = Auth::user();
        $methods = PaymentMethod::where('user_id', $user->id)
        ->orderBy('id', 'desc')
        ->get();
        $methodArr = [];
        $stripe = new Stripe;
        foreach ($methods as $k => $m) {
            $card = $stripe->retrieveMethod($m->p_key);
            if(!empty($card->id)){
                $methodArr[$k] = [
                    // 'id' => $m->id,
                    'uid' => encrypt($m->id),
                    'default' => $m->is_default,
                    'type' => $card->card->brand,
                    'info' => $m->p_meta,
                    'month'=> $card->card->exp_month,
                    'year'=> $card->card->exp_year,
                    'last' => $card->card->last4,
                ];
            }
        }

        return response()->json([
            'status' => true,
            'msg'    => 'Okay',
            'cards'  => $methodArr
        ]);
    }


    /**
     * Make a card Default
     * @param string $id encrypted Id
     * @return Illiminate\Http\Response - JSON
     */
    public function makeDefault($id){
        $user = Auth::user();
        $card = PaymentMethod::where('user_id', $user->id)
        ->where('id', decrypt($id))
        ->first();

        if($card){

            $stripe = new Stripe;
            $up = $stripe->makeDefaultPaymentMethod($user->stripe_id, $card->p_key);
            if(!empty($up->id) AND $up->invoice_settings->default_payment_method == $card->p_key){

                PaymentMethod::where('user_id', $user->id)->update(['is_default' => 0]);
                $card->is_default = 1;
                $card->save();

                return response()->json([
                    'status' => true,
                    'msg'    => 'Selected Payment method is default now.'
                ], 200);

            } else {
                abort(500, 'Unable to update the default card!');
            }

        } else {
            abort(404, 'Card not found!');
        }
    }

    /**
     * Delete a Payment method
     * @param string $id encrypted Id
     * @return Illiminate\Http\Response - JSON
     */
    public function deletePaymentMethod($id){
        $user = Auth::user();
        $card = PaymentMethod::where('user_id', $user->id)
        ->where('id', decrypt($id))
        ->first();

        if($card){
            $stripe = new Stripe;
            $up = $stripe->detachMethod($card->p_key);
            if($up['status']){

                $card->delete();
                return response()->json([
                    'status' => true,
                    'msg'    => 'Card has been deleted successfully.'
                ], 200);

            } else {
                abort(500, $up['msg']);
            }

        } else {
            abort(404, 'Card not found!');
        }
    }

    /**
     * Add Money to wallet
     * @param \App\Http\Requests\TopupRequest $topupRequest Form with Amount and card
     * @return \Illuminate\Http\Response - JSON
     */
    public function walletTopup(TopupRequest $topupRequest){
        $user = Auth::user();
        $user = User::find($user->id);
        $reqData = $topupRequest->only([
            'amount',
            'card'
        ]);
        // return response()->json($reqData,200);
        $card = PaymentMethod::find($reqData['card']);
        $amount = $reqData['amount'];
        $vat = 20;
        $tax = ($amount * $vat)/100;
        $pay = $amount + $tax;
        $remark = "Wallet topup with £".$amount." at ".Carbon::now()->format('h:i A d-m-Y');
        $stripe = new Stripe;
        $stripeResp = $stripe->processPayment($user, $card, $pay, 'gbp', $remark);
        if($stripeResp['status']){
            $charge = $stripeResp['charge'];
            $txn = new PaymentTransaction;
            $txn->user_id = $user->id;
            $txn->txn_id  = Str::uuid();
            $txn->type    = 'topup';
            $txn->paid  = $charge->amount_received / 100;
            $txn->amount  = $amount;
            $txn->tax = $tax;
            $txn->vat = $vat;
            $txn->remark = $remark;
            $txn->p_key = $charge->id;
            if($charge->status == 'succeeded'){
                $txn->status = 1;
                $user->balance += $amount;
                $user->save();

                $trans = new Transaction;
                $trans->payer_id = $trans->receiver_id = $user->id;
                $trans->txn_id = $txn->txn_id;
                $trans->type = 'topup';
                $trans->amount = $amount;
                $trans->status = 1;
                $trans->remark = $remark;
                $trans->save();

                $resp = [
                    'status' => true,
                    'msg'    => "Payment Success. Your Updated wallet balance is £".$user->balance,
                    'balance'=> $user->balance
                ];
            } else if($charge->status == 'processing'){
                $txn->status = 3;
                $resp = [
                    'status' => true,
                    'msg'    => "Payment is under process. Soon it will be updated.",
                    'balance'=> $user->balance
                ];
            } else {
                $txn->status = 4;
                $resp = [
                    'status' => false,
                    'msg'    => "Payment failed. Status: ".strtoupper($charge->status),
                    'balance'=> $user->balance
                ];
            }
            $txn->save();
        } else {
            $resp = $stripeResp;
        }

        return response()->json($resp,200);
    }

    /**
     * Get All Payment Transaction
     * For loggen in user
     * 
     * @return \Illuminate\Http\Response - JSON json data
     */
    public function paymentTrans($after = null){
        $user = User::find(Auth::user()->id); //Auth::user()->id
        if($after){
            $trans = PaymentTransaction::where('user_id', $user->id)
            ->where('id', '<', decrypt($after))
            ->latest('created_at')
            ->take(10)
            ->get();

        } else {
            $trans = PaymentTransaction::where('user_id', $user->id)
            ->latest('created_at')
            ->take(10)
            ->get();
        }
        $transArr = [];
        $last = $after;
        foreach ($trans as $k => $t) {
            $transArr[$k] = [
                'uid' => encrypt($t->id),
                'txn_id' => $t->txn_id,
                'action' => strtoupper($t->type),
                'amount' => $t->amount,
                'paid'   => $t->paid,
                'tax'    => $t->tax,
                'vat'    => $t->vat,
                'status' => $t->txn_status,
                'desc'   => $t->remark,
                'time'   => $t->created_at->format('h:i A d-m-Y')
            ];
            $last = encrypt($t->id);
        }

        return response()->json([
            'txns' => $transArr,
            'last' => $last
        ], 200);
    }

    /**
     * Wallet Transactions History
     * For loggen in User
     * 
     * @return \Illuminate\Http\Response - JSON json data
     */
    public function walletHistory($after = null){
        $user = Auth::user();
        if($after){
            $trans = Transaction::where(function($q) use($user){
                $q->where('payer_id', $user->id)
                ->orWhere('receiver_id', $user->id);
            })
            ->where('id', '<', decrypt($after))
            ->latest('created_at')
            ->take(10)
            ->get();

        } else {
            $trans = Transaction::where('payer_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->latest('created_at')
            ->take(10)
            ->get();
        }

        $last = $after;
        $transArr = [];
        foreach ($trans as $k => $t) {
            $transArr[$k] = [
                'uid' => encrypt($t->id),
                'type' => ucfirst($t->type),
                'txn_id' => $t->txn_id,
                'amount' => $t->amount,
                'status' => $t->txn_status,
                'time'   => $t->created_at->format('h:i A d-m-Y')
            ];
            if($t->type == 'topup'){
                $transArr[$k]['user'] = [
                    'name' => $t->receiver->name,
                    'username' => $t->receiver->username,
                    'avatar' => $t->receiver->avatar,
                    'role' => $t->receiver->role,
                    'is_pro' => $t->receiver->is_pro,
                ];
                $transArr[$k]['txn_type'] = 1;
            } else if($t->payer_id == $user->id){
                $transArr[$k]['user'] = [
                    'name' => $t->receiver->name,
                    'username' => $t->receiver->username,
                    'avatar' => $t->receiver->avatar,
                    'role' => $t->receiver->role,
                    'is_pro' => $t->receiver->is_pro,
                ];
                $transArr[$k]['txn_type'] = 0;
            } else {
                $transArr[$k]['user'] = [
                    'name' => $t->payer->name,
                    'username' => $t->payer->username,
                    'avatar' => $t->payer->avatar,
                    'role' => $t->payer->role,
                    'is_pro' => $t->payer->is_pro,
                ];
                $transArr[$k]['txn_type'] = 1;
            }

            $last = encrypt($t->id);
        }

        return response()->json([
            'txns' => $transArr,
            'last' => $last
        ], 200);
    }

    /**
     * Subscribe to a plan
     * @param string $id Encrypted Plan Id
     * @param int $month For how many months
     * @return \Illuminate\Http\Response json response
     */
    public function subscribe($id, $month = 1){
        $user = User::findOrFail(Auth::user()->id);
        $plan = CreatorPlan::findOrFail(decrypt($id));
        $creator = User::find($plan->user_id);

        if($user->id == $creator->id){
            return response()->json([
                'status' => false,
                'msg'    => 'You are the owner of this plan so can not subscribe to this plan'
            ], 200); 
        }

        $activePromotion = $plan->latestActivePromotion();
        $sub = new Subscription;
        $sub->user_id = $user->id;
        $sub->creator_id = $creator->id;
        $sub->plan_id = $plan->id;
        $sub->amount = $plan->amount;
        if($month === 1){
            $sub->plan_duration = 1;
            if($activePromotion){
                if($user->balance >= $activePromotion['prom_amount']){

                    $sub->amount_paid = $activePromotion['prom_amount'];
                    $sub->discount = $plan->amount - $activePromotion['prom_amount'];
                    $sub->start = Carbon::now();
                    $sub->end = Carbon::now()->addMonth(1);
                    $sub->status = 1;
                    $sub->save();

                    $trans = new Transaction;
                    $trans->payer_id = $user->id;
                    $trans->receiver_id = $creator->id;
                    $trans->type = 'subscription';
                    $trans->txn_id = strtoupper(Str::uuid());
                    $trans->amount = $activePromotion['prom_amount'];
                    $trans->status = 1;
                    $trans->txn_type = 0;
                    $trans->save();

                    $user->balance -= $activePromotion['prom_amount'];
                    $user->save();
                    $creator->balance += $activePromotion['prom_amount'];
                    $creator->save();

                    $resp = [
                        'status' => true,
                        'msg'    => 'Success!'
                    ];

                } else {
                    $resp = [
                        'status' => false,
                        'wallet' => false,
                        'msg'    => 'Insufficient balance. Please add some amount in wallet.'
                    ];
                }
            } else {
                $sub->amount_paid = $plan->amount;

                if($user->balance < $plan->amount){
                    return response()->json([
                        'status' => false,
                        'wallet' => false,
                        'msg'    => 'Insufficient balance. Please add some amount in wallet.'
                    ], 200);
                }

                $sub->start = Carbon::now();
                $sub->end = Carbon::now()->addMonth(1);
                $sub->status = 1;
                $sub->save();

                $trans = new Transaction;
                $trans->payer_id = $user->id;
                $trans->receiver_id = $creator->id;
                $trans->type = 'subscription';
                $trans->txn_id = strtoupper(Str::uuid());
                $trans->amount = $plan->amount;
                $trans->status = 1;
                $trans->txn_type = 0;
                $trans->save();

                $user->balance -= $plan->amount;
                $user->save();
                $creator->balance += $plan->amount;
                $creator->save();
                $resp = [
                    'status' => true,
                    'msg'    => 'Success!'
                ];
            }
        } else if($month == 2 AND !empty($plan->month_2)){

            if($user->balance < $plan->month_2['amount']){
                return response()->json([
                    'status' => false,
                    'wallet' => false,
                    'msg'    => 'Insufficient balance. Please add some amount in wallet.'
                ], 200);
            }

            $sub->plan_duration = 2;
            $sub->amount_paid = $plan->month_2['amount'];
            if($user->balance )
            $sub->discount = ($plan->amount * 2) - $plan->month_2['amount'];
            $sub->start = Carbon::now();
            $sub->end = Carbon::now()->addMonth(2);
            $sub->status = 1;
            $sub->save();

            $trans = new Transaction;
            $trans->payer_id = $user->id;
            $trans->receiver_id = $creator->id;
            $trans->type = 'subscription';
            $trans->txn_id = strtoupper(Str::uuid());
            $trans->amount = $plan->month_2['amount'];
            $trans->status = 1;
            $trans->txn_type = 0;
            $trans->save();

            $user->balance -= $plan->month_2['amount'];
            $user->save();
            $creator->balance += $plan->month_2['amount'];
            $creator->save();
            $resp = [
                'status' => true,
                'msg'    => 'Success!'
            ];
        } else if($month == 3 AND !empty($plan->month_3)){

            if($user->balance < $plan->month_3['amount']){
                return response()->json([
                    'status' => false,
                    'wallet' => false,
                    'msg'    => 'Insufficient balance. Please add some amount in wallet.'
                ], 200);
            }

            $sub->plan_duration = 3;
            $sub->amount_paid = $plan->month_3['amount'];
            $sub->discount = ($plan->amount * 3) - $plan->month_3['amount'];
            $sub->start = Carbon::now();
            $sub->end = Carbon::now()->addMonth(3);
            $sub->status = 1;
            $sub->save();

            $trans = new Transaction;
            $trans->payer_id = $user->id;
            $trans->receiver_id = $creator->id;
            $trans->type = 'subscription';
            $trans->txn_id = strtoupper(Str::uuid());
            $trans->amount = $plan->month_3['amount'];
            $trans->status = 1;
            $trans->txn_type = 0;
            $trans->save();

            $user->balance -= $plan->month_3['amount'];
            $user->save();
            $creator->balance += $plan->month_3['amount'];
            $creator->save();
            $resp = [
                'status' => true,
                'msg'    => 'Success!'
            ];
        } else if($month == 6 AND !empty($plan->month_6)){
            
            if($user->balance < $plan->month_6['amount']){
                return response()->json([
                    'status' => false,
                    'wallet' => false,
                    'msg'    => 'Insufficient balance. Please add some amount in wallet.'
                ], 200);
            }

            $sub->plan_duration = 6;
            $sub->amount_paid = $plan->month_6['amount'];
            $sub->discount = ($plan->amount * 6) - $plan->month_2['amount'];
            $sub->start = Carbon::now();
            $sub->end = Carbon::now()->addMonth(6);
            $sub->status = 1;
            $sub->save();

            $trans = new Transaction;
            $trans->payer_id = $user->id;
            $trans->receiver_id = $creator->id;
            $trans->type = 'subscription';
            $trans->txn_id = strtoupper(Str::uuid());
            $trans->amount = $plan->month_6['amount'];
            $trans->status = 1;
            $trans->txn_type = 0;
            $trans->save();

            $user->balance -= $plan->month_6['amount'];
            $user->save();
            $creator->balance += $plan->month_6['amount'];
            $creator->save();
            $resp = [
                'status' => true,
                'msg'    => 'Success!'
            ];
        } else {
            $resp = [
                'status' => false,
                'wallet' => true,
                'msg'    => 'invalid plan selection.'
            ];
        }

        return response()->json($resp, 200);

    }

    /**
     * List Down Active Subscriptions
     * for logged in user
     * 
     * @return \Illuminate\Http\Response
     */
    public function activeSubscriptions(){
        $user = Auth::user();
        $subs = Subscription::where('user_id', $user->id)
        ->whereStatus(1)
        ->latest('updated_at')
        ->get();

        $subsArr = [];
        foreach($subs as $k => $s){
            $subsArr[$k] = [
                'uid' => encrypt($s->id),
                'paid' => $s->amount_paid,
                'discount' => $s->discount,
                'month'   => $s->plan_duration,
                'start'   => $s->start->format('d M, Y'),
                'end'     => $s->end->format('d M, Y'),
                'status'  => $s->sub_status,
                'plan'    => [
                    'uid'   => encrypt($s->plan->id),
                    'title' => $s->plan->title,  
                ],
                'creator' => [
                    'name' => $s->creator->name,
                    'username' => $s->creator->username,
                    'avatar' => $s->creator->avatar,
                    'role'   => $s->creator->role,
                    'is_pro'   => $s->creator->is_pro,
                ]
            ];
        }

        return response()->json([
            'subscriptions' => $subsArr,
            'total' => count($subsArr)
        ], 200);
    }

    /**
     * List Down Active Subscriptions
     * for logged in user
     * 
     * @return \Illuminate\Http\Response
     */
    public function expiredSubscriptions(){
        $user = Auth::user();
        $subs = Subscription::where('user_id', $user->id)
        ->whereStatus(0)
        ->latest('updated_at')
        ->get();

        $subsArr = [];
        foreach($subs as $k => $s){
            $subsArr[$k] = [
                'uid' => encrypt($s->id),
                'paid' => $s->amount_paid,
                'discount' => $s->discount,
                'month'   => $s->plan_duration,
                'start'   => $s->start->format('d M, Y'),
                'end'     => $s->end->format('d M, Y'),
                'status'  => $s->sub_status,
                'plan'    => [
                    'uid'   => encrypt($s->plan->id),
                    'title' => $s->plan->title,  
                ],
                'creator' => [
                    'name' => $s->creator->name,
                    'username' => $s->creator->username,
                    'avatar' => $s->creator->avatar,
                    'role'   => $s->creator->role,
                    'is_pro'   => $s->creator->is_pro,
                ]
            ];
        }

        return response()->json([
            'subscriptions' => $subsArr,
            'total' => count($subsArr)
        ], 200);
    }

    /**
     * Get Pro Subscriptions
     * @return \Illuminate\Http\Response
     */
    public function proSubscription(){
        $user = User::find(Auth::user()->id);
        if(!empty($user->proSubscription->id)){
            $resp = [
                'status' => true,
                'pro'    => [
                    'uid' => encrypt($user->proSubscription->id),
                    'status' => $user->proSubscription->sub_status,
                    'auto_renew' => $user->proSubscription->auto_renew,
                    'start'  => Carbon::parse($user->proSubscription->start)->format('d-m-Y'),
                    'end'  => Carbon::parse($user->proSubscription->end)->format('d-m-Y'),
                    'cancelled' => $user->proSubscription->is_cancelled,
                    'cancelled_at' => $user->proSubscription->cancelled_at ? $user->proSubscription->cancelled_at->diffForHumans() : false,
                    'amount' => $user->proSubscription->amount
                ]
            ];
        } else {
            $resp = [
                'status' => false,
                'msg'    => 'No Subscription found!'
            ];
        }

        return response()->json($resp, 200);
    }

    /**
     * Subscribe to Creator Pro Plan
     * 
     * @param \App\Http\Requests\ProSubscribeRequest $proSubscribeRequest
     * @return \Illuminate\Http\Response json response
     */
    public function proSubscribe(ProSubscribeRequest $proSubscribeRequest){
        $user = User::find(Auth::user()->id);
        $reqData = $proSubscribeRequest->only([
            'auto_renew',
            'via',
            'card'
        ]);

        $vat = 20;
        $amount = 89;
        $tax = ($amount * $vat)/100;
        $amount_paid = number_format($amount + $tax, 2);
        if($user->balance >= $amount_paid){

            $trans = new Transaction;
            $trans->payer_id = $user->id;
            $trans->receiver_id = $user->id;
            $trans->type = 'pro-subscription';
            $trans->txn_id = strtoupper(Str::uuid());
            $trans->amount = $amount_paid;
            $trans->status = 1;
            $trans->txn_type = 0;
            $trans->save();

            $proSub = new ProSubscription;
            $proSub->user_id = $user->id;
            $proSub->amount = $amount;
            $proSub->vat = $vat;
            $proSub->tax = $tax;
            $proSub->amount_paid = $amount_paid;
            $proSub->start = Carbon::now();
            $proSub->end = Carbon::now()->addMonth(1);
            $proSub->auto_renew = $reqData['auto_renew'];
            $proSub->via = $reqData['via'] ?? 'wallet';
            $proSub->method = !empty($reqData['card'])  ?? NULL;

            $user->balance -= $amount_paid;
            $user->is_pro = 1;
            $user->save();
            $proSub->save();

            $resp = [
                'status' => true,
                'msg'    => 'Congratulations! Now you are a Pro Creator.'
            ];

        } else {
            $resp = [
                'status' => false,
                'wallet' => false,
                'msg'    => 'Insufficient balance, please add some amount in the wallet.'
            ];
        }

        return response()->json($resp, 200);
    }

    /**
     * Update Pro Subscription Auto Renew Status
     * @param string $id  Encrypted Subscription Id
     * @param bool $status True if auto renew else false
     * @return \Illuminate\Http\Response json response
     */
    public function autoRenewUpdate($id, $status){

        $user = Auth::user();
        $sub = ProSubscription::where('user_id', $user->id)
        ->where('id', decrypt($id))
        ->first();
        if($sub){
            $sub->auto_renew = $status;
            $sub->save();
            return response()->json([
                'status' => true,
                'msg'    => 'Auto renew has been '.($status ? 'enabled' : 'disabled'). ' successfully.'
            ], 200);

        } else {
            return response()->json([
                'status' => false,
                'msg'    => 'Invalid Parameters'
            ], 200);
        }



    }

    /**
     * Cancel Pro Subcription
     * 
     * @return \Illuminate\Http\Response JSON response
     */
    public function cancelProSubscription(){
        $user = User::findOrFail(Auth::user()->id);
        $sub = ProSubscription::where('user_id', $user->id)
        ->whereStatus(1)
        ->first();

        if($sub){

            $sub->is_cancelled = 1;
            $sub->save();

            $resp = [
                'status' => false,
                'msg'    => 'Pro subscription has been cancelled and you pro benefits will be ended after '.Carbon::parse($sub->end)->diffForHumans()
            ];
        
        } else {
            $resp = [
                'status'    => false,
                'msg'       => 'No active subscription found.'
            ];
        }

        return response()->json($resp, 200);
    }

    /**
     * Renew / Activate Pro Subscription
     * @param string $id Encrypted Subscription Id
     * @return \Illuminate\Http\Response JSON response
     */
    public function renewProSubscription($id){
        $user = User::find(Auth::user()->id);
        $proSub = ProSubscription::where('user_id', $user->id)
        ->where('id', decrypt($id))
        ->first();

        if($proSub){

            $vat = 20;
            $amount = 89;
            $tax = ($amount * $vat)/100;
            $amount_paid = number_format($amount + $tax, 2);

            $proSub->amount = $amount;
            $proSub->vat = $vat;
            $proSub->tax = $tax;
            $proSub->amount_paid = $amount_paid;
            $proSub->start = Carbon::now();
            $proSub->end = Carbon::now()->addMonth(1);
            $proSub->via = 'wallet';
            $proSub->method = !empty($reqData['card'])  ?? NULL;

            $user->balance -= $amount_paid;
            $user->is_pro = 1;
            $user->save();

            $proSub->is_cancelled = NULL;
            $proSub->cancelled_at = NULL;
            $proSub->status = 1;
            $proSub->save();

        } else {

            return response()->json([
                'status' => false,
                'msg'    => 'Invalid Parameters'
            ], 200);

        }

    }

    
}
