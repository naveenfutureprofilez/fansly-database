<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Http\Requests\TipRequest;
use App\Http\Requests\TopupRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PaymentMethod;
use App\Models\PaymentTransaction;
use App\Models\Post;
use App\Models\Tip;
use App\Models\Transaction;
use App\Stripe;
use App\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TipsController extends Controller
{
    /**
     * Direct Tip Send to a user
     * 
     * @param string $id Encrypted Id of the target User
     * @param \App\Http\Requests\TipRequest $tipRequest 
     * @return \Illuminate\Http\Response - JSON Json Response
     */
    public function directTip($id, TipRequest $tipRequest){
        $user = User::find(Auth::user()->id);
        $target = User::findOrFail(decrypt($id));
        $reqData = $tipRequest->only([
            'amount',
            'message',
            'available'
        ]);

        if($reqData['available']){

            $trans = new Transaction;
            $trans->payer_id = $user->id;
            $trans->receiver_id = $target->id;
            $trans->type = 'tip';
            $trans->txn_id = strtoupper(Str::uuid());
            $trans->amount = $reqData['amount'];
            $trans->status = 1;
            $trans->txn_type = 0;
            $trans->save();

            $tip = new Tip;
            $tip->user_id = $user->id;
            $tip->receiver_id = $target->id;
            $tip->tip_type = 'direct';
            $tip->message = $reqData['message'] ?? '';
            $tip->txn_id = $trans->id;
            $tip->amount = $trans->amount;
            $tip->status = 1;
            $tip->save();

            /**
             * Transaction for Receiver
             */
            // $transR = new Transaction;
            // $transR->payer_id = $user->id;
            // $transR->receiver_id = $target->id;
            // $transR->type = 'tip';
            // $transR->txn_id = $trans->txn_id;
            // $transR->amount = $reqData['amount'];
            // $transR->status = 1;
            // $transR->txn_type = 1;
            // $transR->save();

            $user->balance -= $tip->amount;
            $user->save();
            $target->balance += $tip->amount;
            $target->save();

            UserNotification::tipDirect($tip);

            $resp = [
                'status' => true,
                'msg'    => 'Tip of amount £'.$tip->amount.' has been sent successfully.'
            ];
        } else {
            $resp = [
                'status' => false,
                'msg'    => 'Insufficient wallet balance. Please add some amount.',
                'balance'=> $user->balance
            ];
        }

        return response()->json($resp, 200);
    }

    /**
     * Tip Send to a user via post
     * 
     * @param string $post Encrypted Id of the Post
     * @param \App\Http\Requests\TipRequest $tipRequest 
     * @return \Illuminate\Http\Response - JSON Json Response
     */
    public function postTip($post, TipRequest $tipRequest){
        $user = User::find(Auth::user()->id);
        $target = Post::findOrFail(decrypt($post));
        $author = User::find($target->user_id);
        $reqData = $tipRequest->only([
            'amount',
            'message',
            'available'
        ]);

        if($reqData['available']){

            $trans = new Transaction;
            $trans->payer_id = $user->id;
            $trans->receiver_id = $author->id;
            $trans->type = 'tip';
            $trans->txn_id = strtoupper(Str::uuid());
            $trans->amount = $reqData['amount'];
            $trans->status = 1;
            $trans->save();

            $tip = new Tip;
            $tip->user_id = $user->id;
            $tip->receiver_id = $author->id;
            $tip->tip_type = 'direct';
            $tip->message = $reqData['message'] ?? '';
            $tip->txn_id = $trans->id;
            $tip->amount = $trans->amount;
            $tip->status = 1;
            $tip->save();

            $user->balance -= $tip->amount;
            $user->save();
            $author->balance += $tip->amount;
            $author->save();

            UserNotification::tipOnPost($target, $tip);

            $resp = [
                'status' => true,
                'msg'    => 'Tip of amount £'.$tip->amount.' has been sent successfully.'
            ];
        } else {
            $resp = [
                'status' => false,
                'msg'    => 'Insufficient wallet balance. Please add some amount.',
                'balance'=> $user->balance
            ];
        }

        return response()->json($resp, 200);
    }
}
