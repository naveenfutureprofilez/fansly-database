<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBankMethodRequest;
use App\Http\Requests\UpdatePaypalMethodRequest;
use App\Http\Requests\WithdrawRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\Transaction;
use App\Models\CreatorPlan;
use App\Models\Subscription;
use App\Models\PayoutMethod;
use App\Models\PayoutRequest;
use App\Models\PayoutTranscation;
use App\Stripe;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PayoutController extends Controller
{
    
    /**
     * Get Payout Methods
     * For logged in user
     * 
     * @return \Illuminate\Http\Response JSON response
     */
    public function payoutMethods(){
        $user = User::find(Auth::user()->id);
        $methodData = ['user_id' => $user->id];
        $methods = PayoutMethod::firstOrCreate($methodData);

        return response()->json([
            'paypal' => $methods->paypal,
            'bank'   => $methods->bank
        ]);
    }


    /**
     * Updated PayPal Email
     * @param \App\Http\Requests\UpdatePaypalMethodRequest $updatePaypalMethodRequest
     * @return \Illuminate\Http\Response JSON response
     */
    public function updatePaypal(UpdatePaypalMethodRequest $updatePaypalMethodRequest){
        $reqData = $updatePaypalMethodRequest->only(['email']);
        $user = Auth::user();

        $method = PayoutMethod::firstOrCreate(['user_id' => $user->id]);
        $method->paypal = $reqData['email'];
        $method->save();

        return response()->json([
            'status' => true,
            'msg'    => 'Paypal Updated Successfully.'
        ]);

    }

    /**
     * Updated bank Deatils
     * @param \App\Http\Requests\UpdateBankMethodRequest $updatePaypalMethodRequest
     * @return \Illuminate\Http\Response JSON response
     */
    public function updateBank(UpdateBankMethodRequest $updateBankMethodRequest){
        $reqData = $updateBankMethodRequest->only(['bank']);
        $user = Auth::user();

        $method = PayoutMethod::firstOrCreate(['user_id' => $user->id]);
        $method->bank = $reqData['bank'];
        $method->save();

        return response()->json([
            'status' => true,
            'msg'    => 'Bank Details Updated Successfully.'
        ]);

    }

    /**
     * Get Avaialble withdrawable Payout amount
     * Logged in creater
     * 
     * @return mixed $available | Json Response
     */
    public function availablePayout($in = false){
        $user = User::find(Auth::user()->id);
        if($user->balance >= 100){
            if($user->is_pro)
                $available = floor(($user->balance * 100) / (100 + 6));
            else
                $available = floor(($user->balance * 100) / (100 + 15));
        } else {
            $available = 0;
        }
        
        if($in){
            return $available;
        } else {
            
            return response()->json([
                'available' => $available
            ], 200);
        }
    }

    /**
     * Make a Payout Request
     * Logged In Creator
     * 
     * @param \App\Http\Requests\WithdrawRequest $withdrawRequest
     * @return \illuminate\Http\Response json response
     */
    public function payoutRequest(WithdrawRequest $withdrawRequest){
        $user = User::find(Auth::user()->id);
        $avail = $this->availablePayout(true);
        if($avail !== 0){
            
            $reqData = $withdrawRequest->only([
                'amount',
                'type'
            ]);
            if($reqData['amount'] <=  $avail){

                $amount = $reqData['amount'];
                if($user->is_pro){
                    $fee = 6;
                } else {
                    $fee = 15;
                }
                $feeAmount = $amount * $fee / 100;
                $deduct = $feeAmount + $amount;
                $trans = new Transaction;
                $trans->payer_id = $trans->receiver_id = $user->id;
                $trans->txn_id = Str::uuid();
                $trans->type = 'withdraw';
                $trans->amount = $deduct;
                $trans->status = 1;
                $trans->remark = 'Withdraw request for £'. $amount .' at '.Carbon::now()->format('h:i A d-m-Y');
                $trans->save();

                $pout = new PayoutRequest;
                $pout->user_id = $user->id;
                $pout->wallet_txn_id = $trans->id;
                $pout->payout_type = $reqData['type'];
                $pout->amount = $amount;
                $pout->fee = $feeAmount;
                $pout->deduct = $deduct;
                $pout->save();

                $user->balance -= $deduct;
                $user->save();

                $resp = [
                    'status' => true,
                    'msg'    => 'Payout request generated.'
                ];

            } else {
                $resp = [
                    'status' => false,
                    'msg'    => 'Please enter amount less or equal to '.$avail
                ];
            }

        } else {
            $resp = [
                'status' => false,
                'msg'    => 'You don\'t have anough amount to withdraw.'
            ];
        }

        return response()->json($resp, 200);
    }

    /**
     * Payout History for Creator
     * Recent 10 Request in each payload
     * 
     * @param mixed $after=false Get Load more after the id
     * @return \Illuminate\Http\Response json response
     */
    public function payoutHistory($after = false){
        $user = Auth::user();

        $query = PayoutRequest::where('user_id', $user->id);
        if($after){
            $query->where('id', '<', decrypt($after));
        }
        $reqs = $query->orderBy('id', 'desc')->take(10)->get();

        $reqsArr = [];
        $last = $after;

        foreach($reqs as $k => $r){
            $reqsArr[$k] = [
                'amount' => $r->amount,
                'fee'    => $r->fee,
                'wallet_txn_id' => $r->wallet_txn_id,
                'deduct'        => $r->deduct,
                'payout_type'   => $r->payout_type,
                'pay_status'    => $r->txn_status,
                'time'          => $r->created_at->format('h:i A d-m-Y')
            ];

            if(in_array($r->status, [1,2]) AND !empty($r->txn->id)){
                $reqsArr[$k]['txn'] = [
                    'txn_id' => $r->txn->txn_id,
                    'payout_details' => $r->txn->payout_details,
                    'amount'  => $r->txn->amount,
                    'remark'  => $r->txn->remark,
                    'time'    => $r->txn->created_at->format('h:i A d-m-Y')
                ];
            } else {
                $reqsArr[$k]['txn'] = false;
            }

            $last = encrypt($r->id);
        }

        return response()->json([
            'transactions' => $reqsArr,
            'last'     => $last
        ], 200);
    }
}
