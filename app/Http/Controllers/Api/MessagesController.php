<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Models\Conversation;
use App\Models\CreatorSetting;
use App\Models\Message;
use App\Models\MessageMedia;
use App\Models\MessagePayment;
use App\Models\TempFile;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
use Illuminate\Database\Eloquent\Builder;

class MessagesController extends Controller
{
    
    /**
     * To send a message
     * 
     * @param mixed $id Id of targeted user
     * @param \App\Http\Requests\MessageRequest $messageRequest
     * @return \Illuminate\Http\Response json response
     */
    public function sendMessage($id, MessageRequest $messageRequest){

        $user = Auth::user();
        $id = decrypt($id);
        $conversation = Conversation::getConversation($id);

        $t = User::find($id);
        if($t->role == 1){
            if($t->creatorSettings()->exists() AND $t->creatorSettings->paid_msg !== 0){

                if($t->creatorSettings->paid_msg == 1 AND !empty($t->creatorSettings->paid_msg_amount)){
                    $amount = $t->creatorSettings->paid_msg_amount;
                    $user = User::find($user->id);

                    if($amount > $user->balance){

                        return response()->json([
                            'status' => false,
                            'wallet' => false,
                            'msg'    => 'Insufficient balance in wallet. Please add some balance.'
                        ]);

                    } else {

                        $trans = new Transaction();
                        $trans->payer_id = $user->id;
                        $trans->receiver_id = $t->id;
                        $trans->type = 'paid-message';
                        $trans->txn_id = strtoupper(Str::uuid());
                        $trans->amount = $amount;
                        $trans->status = 1;
                        $trans->txn_type = 0;
                        $trans->save();

                        $user->balance -= $amount;
                        $user->save();

                        $t->balance += $amount;
                        $t->save();

                    }
                }
            }
        }


        $reqData = $messageRequest->safe()->only([
            'msg',
            'media',
            'is_locked',
            'lock_price'
        ]);
        $msg = new Message;
        $msg->from_id = $user->id;
        $msg->to_id = $id;
        $msg->conversation_id = $conversation->id;
        $msg->message = $reqData['msg'];
        $msg->save();
        $conversation->updated_at = Carbon::now();
        $conversation->save();
        
        if($user->role == 1){
            $media = $messageRequest->file('media');
            if($media){

                $data = [];
                $data['name'] = $media->getClientOriginalName();
                $data['mime'] = $media->getClientMimeType();
                $data['size'] = $media->getSize();
                $data['type'] = TempFile::getMediaType($data['mime']);
                $data['uid']  = Str::uuid();
                $ext = $media->extension();
                $fileName = $data['uid'].'.'.$ext;
                $media->storeAs('public/msg/', $fileName);
                // if($data['type'] == 'image'){
                //     $wImg = Image::make($media->getRealPath());
                //     $wm = storage_path('app/public/watermark/default.png');
                //     $wImg->insert($wm, 'bottom-right', 0, 0);
                //     $wImg->encode($ext);
                //     $wImg->save(storage_path('app/public/msg/'.$fileName));
                // } else {
                //     $media->storeAs('public/msg/', $fileName);
                // }

                $store = new MessageMedia;
                $store->message_id = $msg->id;
                $store->name = $data['name'];
                $store->type = $data['type'];
                $store->size = $data['size'];
                $store->mime = $data['mime'];
                $store->ext = $ext;
                $store->uid = $data['uid'];
                $store->is_locked = $reqData['is_locked'] ?? 0;
                $store->lock_price = $reqData['lock_price'] ?? 0;
                $store->save();

                // $media->storeAs('public/message/', $fileName);
            }
        }
        $file = false;
        if(!empty($store->id)){
            $file = [
                'uid' => encrypt($store->id),
                'type' => $store->type,
                'name' => $store->full_name,
                'url' => Storage::url('public/msg/'.$store->full_name),
                'is_locked' => $store->is_locked,
                'lock_price' => $store->lock_price,
            ];
        }
        return response()->json([
            'status' => true,
            'msg' => [
                [
                    'uid' => encrypt($msg->id),
                    'text' => $msg->message,
                    'read_at' => empty($msg->read_at) ? false : Carbon::parse($msg->read_at)->format('h:i A d-m-Y'),
                    'reaction' => $msg->reaction,
                    'time' => $msg->created_at->diffForHumans(),
                    'sender' => true,
                    'file' => $file
                ]
            ]
        ]);
    }

    /**
     * Send paid message
     * If Creator set messages for paid
     * 
     * @param \App\Http\Requests\MessageRequest $messageRequest
     * @return \Illuminate\Http\Response json response
     */
    public function sendPaidMessage(){

    }

    /**
     * Get Recent Conversations
     * with Lates messages
     * 
     * @return \Illuminate\Http\Response json response
     */
    public function conversations(){
        $user = User::find(Auth::user()->id);
        $cons = Conversation::whereRaw("FIND_IN_SET($user->id, users)")
        ->latest('updated_at')
        ->get();

        $consArr = [];

        foreach ($cons as $k => $c) {

            $unread = Message::where('conversation_id', $c->id)
            ->where('to_id', $user->id)
            ->whereNull('read_at')
            ->count();

            $paid = false;
            $amount = false;
            if(empty($c->latest)){
                $tUser = str_replace([$user->id, ','], '', $c->users);
                $target = User::find($tUser);
                if($target->role == 1){
                    $setting = CreatorSetting::where('user_id', $target->id)->first();
                    $paid = $setting->paid_msg ?? false;
                    $amount = $setting->paid_msg_amount ?? 0;
                }
                $consArr[$k] = [
                    'uid' => encrypt($c->id),
                    'user' => [
                        'uid' => encrypt($target->id),
                        'name'=> $$target->name,
                        'username'=> $target->username,
                        'avatar'=> $target->avatar,
                        'banner'=> $target->banner,
                        'role'=> $target->role,
                        'is_pro'=> $target->is_pro,
                        'paid'  => $paid,
                        'amount'  => $amount,
                    ],
                    'msg' => false,
                    'unread' => 0,
                    'time' => $c->updated_at->diffForHumans()
                ];
            } else {

                if($c->latest->from_id == $user->id){
                    
                    if($c->latest->to->role == 1){
                        $setting = CreatorSetting::where('user_id', $c->latest->to->id)->first();
                        $paid = $setting->paid_msg ?? false;
                        $amount = $setting->paid_msg_amount ?? 0;
                    }

                    $consArr[$k] = [
                        'uid' => encrypt($c->id),
                        'user' => [
                            'uid' => encrypt($c->latest->to_id),
                            'name'=> $c->latest->to->name,
                            'username'=> $c->latest->to->username,
                            'avatar'=> $c->latest->to->avatar,
                            'banner'=> $c->latest->to->banner,
                            'role'=> $c->latest->to->role,
                            'is_pro'=> $c->latest->to->is_pro,
                            'paid'  => $paid,
                            'amount'  => $amount,
                        ],
                        'msg' => [
                            'uid' => encrypt($c->latest->id),
                            'text'=> $c->latest->message,
                            'read' => true,
                            'file' => !empty($c->latest->media->id)
                        ],
                        'unread' => $unread,
                        'time' => $c->updated_at->diffForHumans(),
                    ];
                } else {

                    if($c->latest->from->role == 1){
                        $setting = CreatorSetting::where('user_id', $c->latest->from->id)->first();
                        $paid = $setting->paid_msg ?? false;
                        $amount = $setting->paid_msg_amount ?? 0;
                    }

                    $consArr[$k] = [
                        'uid' => encrypt($c->id),
                        'user' => [
                            'uid' => encrypt($c->latest->from_id),
                            'name'=> $c->latest->from->name,
                            'username'=> $c->latest->from->username,
                            'avatar'=> $c->latest->from->avatar,
                            'banner'=> $c->latest->from->banner,
                            'role'=> $c->latest->from->role,
                            'is_pro'=> $c->latest->from->is_pro,
                            'paid'  => $paid,
                            'amount'  => $amount,
                        ],
                        'msg' => [
                            'uid' => encrypt($c->latest->id),
                            'text'=> $c->latest->message,
                            'read' => !empty($c->latest->read_at),
                            'file' => !empty($c->latest->media->id)
                        ],
                        'unread' => $unread,
                        'time' => $c->updated_at->diffForHumans()
                    ];
                }
                
            }
        }

        return response()->json([
            'conversations' => $consArr
        ]);
    }

    /**
     * Get Messages for a user
     * 
     * @param string $id encrypted user Id
     * @return \Illuminate\Http\Response json response
     */
    public function msgs($id){
        $user = User::find(Auth::user()->id);
        // $user = User::find(29);
        $id = decrypt($id);
        $conversation = Conversation::getConversation($id);

        $msgs = Message::where('conversation_id', $conversation->id)
        ->orderBy('id', 'desc')
        ->take(50)
        ->get();

        $update = Message::where('conversation_id', $conversation->id)
        ->where('to_id', $user->id)
        ->whereNull('read_at')
        ->update([
            'is_read' => 1,
            'read_at' => Carbon::now()
        ]);

        $msgsArr = [];
        $last = false;
        $more = false;

        foreach($msgs as $k => $m){
            if(!$last){
                $last = encrypt($m->id);
            }

            // $paid = false;
            // $amount = false;
            $file = false;
            $purchased = false;

            if($m->from_id !== $user->id){
                if(!empty($m->media->id)){
                    if($m->media->is_locked){
                        $purchased = $m->isPurchased($m->to_id);
                    } else {
                        $purchased = true;
                    }
                    if($purchased){

                        $file = [
                            'uid' => encrypt($m->media->id),
                            'type' => $m->media->type,
                            'name' => $m->media->full_name,
                            'url' => Storage::url('public/msg/'.$m->media->full_name),
                            'is_locked' => $m->media->is_locked,
                            'lock_price' => $m->media->lock_price,
                            'purchased' => $purchased
                        ];

                    } else {

                        $file = [
                            'uid' => encrypt($m->media->id),
                            'type' => $m->media->type,
                            'is_locked' => $m->media->is_locked,
                            'lock_price' => $m->media->lock_price,
                            'purchased' => $purchased
                        ];
                    }
                }
            } else {
                if(!empty($m->media->id)){
                    $file = [
                        'uid' => encrypt($m->media->id),
                        'type' => $m->media->type,
                        'name' => $m->media->full_name,
                        'url' => Storage::url('public/msg/'.$m->media->full_name),
                        'is_locked' => $m->media->is_locked,
                        'lock_price' => $m->media->lock_price,
                    ];
                }
            }
            $msgsArr[$k] = [
                'uid' => encrypt($m->id),
                'text' => $m->message,
                'reaction' => $m->reaction,
                'sender' => $m->from_id == $user->id,
                'read_at' => empty($m->read_at) ? false : Carbon::parse($m->read_at)->format('h:i A d-m-Y'),
                'time'   => $m->created_at->diffForHumans(),
                'file' => $file
            ];

            $more = encrypt($m->id);
        }

        $msgsArr = array_reverse($msgsArr);

        return response()->json([
            'msgs' => $msgsArr,
            'last' => $last,
            'more' => $more,
            'cid'  => $conversation->id
        ]);

    }

    /**
     * Load more in a chat
     * previous messages
     * 
     * @param string $c Encrypted Conversation Id
     * @param string $m Previously loaded messaes's init pointer
     * @return \Illuminate\Http\Response json response
     */
    public function loadMore($c, $m){
        $user = Auth::user();
        // $cons = Conversation::where('id', decrypt($c))
        // ->whereRaw("FIND_IN_SET($user->id, users)")
        // ->first();
        $c = decrypt($c);
        $cons = Conversation::getConversation($c);
        if($cons){
            $more = false;
            $msgs = Message::where('conversation_id', $cons->id)
            ->where('id', '<', decrypt($m))
            ->orderBy('id', 'desc')
            ->take(20)
            ->get();

            foreach($msgs as $k => $m){
                
                $file = false;
                $purchased = false;

                if($m->from_id !== $user->id){
                    if(!empty($m->media->id)){
                        $purchased = $m->isPurchased($m->to_id);
                        if($purchased){
                            $file = [
                                'uid' => encrypt($m->media->id),
                                'type' => $m->media->type,
                                'name' => $m->media->full_name,
                                'url' => Storage::url('public/msg/'.$m->media->full_name),
                                'is_locked' => $m->media->is_locked,
                                'lock_price' => $m->media->lock_price,
                                'purchased' => $purchased
                            ];
                        } else {

                            $file = [
                                'uid' => encrypt($m->media->id),
                                'type' => $m->media->type,
                                'is_locked' => $m->media->is_locked,
                                'lock_price' => $m->media->lock_price,
                                'purchased' => $purchased
                            ];
                        }
                    }
                } else {
                    if(!empty($m->media->id)){
                        $file = [
                            'uid' => encrypt($m->media->id),
                            'type' => $m->media->type,
                            'name' => $m->media->full_name,
                            'url' => Storage::url('public/msg/'.$m->media->full_name),
                            'is_locked' => $m->media->is_locked,
                            'lock_price' => $m->media->lock_price,
                        ];
                    }
                }

                $msgsArr[$k] = [
                    'uid' => encrypt($m->id),
                    'text' => $m->message,
                    'reaction' => $m->reaction,
                    'sender' => $m->from_id == $user->id,
                    'read_at' => empty($m->read_at) ? false : Carbon::parse($m->read_at)->format('h:i A d-m-Y'),
                    'time'   => $m->created_at->diffForHumans(),
                    'file' => $file
                ];
    
                $more = encrypt($m->id);
            }
    
            $msgsArr = array_reverse($msgsArr);
    
            return response()->json([
                'msgs' => $msgsArr,
                'more' => $more
            ]);

        } else {
            abort(403, 'Invalid Access!');
        }
    }

    /**
     * Get Recent Message
     * After last msg or first message
     * 
     * @param string $c Conversation id
     * @param mixed $last message Id
     * @return \Illuminate\Http\Response json response
     */
    public function recentMessage($c, $last = false){
        $user = Auth::user();
        $c = decrypt($c);
        $cons = Conversation::getConversation($c);

        if($cons){

            $query = Message::where('conversation_id', $cons->id);
            if($last){
                $query->where('id', '>', decrypt($last));
            }

            $msgs = $query->orderBy('id', 'asc')
            ->get();

            $update = Message::where('conversation_id', $cons->id)
            ->where('to_id', $user->id)
            ->whereNull('read_at')
            ->update([
                'is_read' => 1,
                'read_at' => Carbon::now()
            ]);
            $msgsArr = [];
            foreach($msgs as $k => $m){
                
                $file = false;
                $purchased = false;

                if($m->from_id !== $user->id){
                    if(!empty($m->media->id)){
                        $purchased = $m->isPurchased($m->to_id);
                        if($purchased){
                            $file = [
                                'uid' => encrypt($m->media->id),
                                'type' => $m->media->type,
                                'name' => $m->media->full_name,
                                'url' => Storage::url('public/msg/'.$m->media->full_name),
                                'is_locked' => $m->media->is_locked,
                                'lock_price' => $m->media->lock_price,
                                'purchased' => $purchased
                            ];
                        } else {

                            $file = [
                                'uid' => encrypt($m->media->id),
                                'type' => $m->media->type,
                                'is_locked' => $m->media->is_locked,
                                'lock_price' => $m->media->lock_price,
                                'purchased' => $purchased
                            ];
                        }
                    }
                } else {
                    if(!empty($m->media->id)){
                        $file = [
                            'uid' => encrypt($m->media->id),
                            'type' => $m->media->type,
                            'name' => $m->media->full_name,
                            'url' => Storage::url('public/msg/'.$m->media->full_name),
                            'is_locked' => $m->media->is_locked,
                            'lock_price' => $m->media->lock_price,
                        ];
                    }
                }

                $msgsArr[$k] = [
                    'uid' => encrypt($m->id),
                    'text' => $m->message,
                    'reaction' => $m->reaction,
                    'sender' => $m->from_id == $user->id,
                    'read_at' => empty($m->read_at) ? false : Carbon::parse($m->read_at)->format('h:i A d-m-Y'),
                    'time'   => Carbon::parse($m->created_at)->format('h:i A d-m-Y'),
                    'file'   => $file,
                ];
    
                $last = encrypt($m->id);
            }
    
            $msgsArr = array_reverse($msgsArr);
    
            return response()->json([
                'msgs' => $msgsArr,
                'last' => $last
            ]);

        } else {
            abort(403, 'Invalid Access!');
        }
    }

    /**
     * Check Read Status of a message
     * 
     * @param string $msg Message Id
     * @return \Illuminate\Http\Response json response
     */
    public function readStatus($msg){

        $m = Message::findOrFail(decrypt($msg));

        if(empty($m->read_at)){
            $read = false;
        } else {
            $read = $m->read_at->format('h:i A d-m-Y');
        }

        return response()->json([
            'status' => $read
        ]);
    }

    /**
     * Get Unread Message Count
     * 
     * @return \Illuminate\Http\Response json response
     */
    public function unreadCount(){
        return response()->json([
            'unread' => Message::where('to_id', Auth::user()->id)
            ->whereNull('read_at')
            ->count()
        ], 200);
    }

    /**
     * Purchase a message Media content
     * 
     * @param string $id Message's encrypted id
     * @return \Illuminate\Http\Response json response
     */
    public function purchaseMessageMedia($id){
        $user = User::findOrFail(Auth::user()->id);
        $id = decrypt($id);
        $msg = Message::where('id', $id)
        ->where('to_id', $user->id)
        ->first();

        if($msg AND !empty($msg->media->id)){
            $file = [
                'uid' => encrypt($msg->media->id),
                'type' => $msg->media->type,
                'name' => $msg->media->full_name,
                'url' => Storage::url('public/msg/'.$msg->media->full_name),
                'is_locked' => $msg->media->is_locked,
                'lock_price' => $msg->media->lock_price,
                'purchased' => true
            ];
            $purchased = $msg->isPurchased($user->id);
            if($purchased){
                $resp = [
                    'status' => true,
                    'wallet' => true,
                    'msg'    => 'Already Purchased',
                    'file'   => $file 
                ];
            } else {

                $sender = User::find($msg->from_id);
                if($user->balance < $msg->media->lock_price){

                   $resp = [
                       'status' => false,
                       'wallet' => false,
                       'msg'    => 'Insufficient balance in wallet. Please add some balance.'
                   ];

                } else {

                    $amount = $msg->media->lock_price;
                    $trans = new Transaction();
                    $trans->payer_id = $user->id;
                    $trans->receiver_id = $sender->id;
                    $trans->type = 'message-media';
                    $trans->txn_id = strtoupper(Str::uuid());
                    $trans->amount = $amount;
                    $trans->status = 1;
                    $trans->txn_type = 0;
                    $trans->save();

                    $user->balance -= $amount;
                    $user->save();

                    $sender->balance += $amount;
                    $sender->save();

                    $pay = new MessagePayment;
                    $pay->message_id = $msg->id;
                    $pay->user_id = $user->id;
                    $pay->amount = $amount;
                    $pay->status = 1;
                    $pay->paid_via = 'wallet';
                    $pay->txn_id = $trans->txn_id;
                    $pay->save();

                    $resp = [
                        'status' => true,
                        'wallet' => true,
                        'msg'    => 'Purchased successfully!',
                        'file'   => $file 
                    ];
                }

            }

        } else {
            $resp = [
                'status' => false,
                'wallet' => true,
                'msg'    => "Message/media not found"
            ];
        }

        return response()->json($resp, 200);
    }

}
