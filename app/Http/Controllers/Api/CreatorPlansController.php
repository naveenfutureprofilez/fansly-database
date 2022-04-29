<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatorPlanRequest;
use App\Http\Requests\PlanPromotionRequest;
use App\Models\CreatorPlan;
use App\Models\PlanPromotion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreatorPlansController extends Controller
{
    public function createPlan(CreatorPlanRequest $creatorPlanRequest){
        $data = $creatorPlanRequest->only([
            'title',
            'amount',
            'benefits',
            'month_2',
            'month_3',
            'month_6',
            'promotion',
            'prom_amount',
            'avail_from',
            'avail_to'
        ]);

        // $user_id = 31;
        $user_id = Auth::user()->id;
        $plan = new CreatorPlan();
        $plan->user_id = $user_id;
        $plan->title = $data['title'];
        $plan->amount = $data['amount'];
        $plan->benefits = empty($data['benefits'])? [] : explode("\n", $data['benefits']);
        $plan->month_2 = $data['month_2']?? NULL;
        $plan->month_3 = $data['month_3']?? NULL;
        $plan->month_6 = $data['month_6']?? NULL;
        $plan->save();

        if(!empty($data['promotion'])){
            $prom = new PlanPromotion();
            $prom->user_id = $plan->user_id;
            $prom->plan_id = $plan->id;
            $prom->prom_amount = $data['prom_amount'];
            $prom->avail_from = Carbon::parse($data['avail_from']);
            $prom->avail_to = Carbon::parse($data['avail_to']);
            $prom->save();
        }


        return response()->json([
            'status' => true,
            'msg'    => 'Okay',
            'plan'   => encrypt($plan->id),
            'prom'   => !empty($prom->id) ? encrypt($prom->id) : null
        ], 200);
    }
    
    public function getPlan($planId){
        // $user_id = 31;
        // $user_id = 31;
        $user_id = Auth::user()->id;
        $planId = decrypt($planId);
        $plan = CreatorPlan::with('promotions')->findOrFail($planId);
        if($plan->user_id !== $user_id){
            abort(403);
        }

        $planData = [
            'title' => $plan->title,
            'amount' => $plan->amount,
            'benefits' => implode("\n", $plan->benefits),
            'month_2' => $plan->month_2,
            'month_3' => $plan->month_3,
            'month_6' => $plan->month_6,
            'status'  => $plan->status
        ];
        foreach($plan->promotions as $prom){
            $start = new Carbon($prom->avail_from);
            $end = new Carbon($prom->avail_to);
            $planData['promotion'][] = [
                'id' => $prom->id,
                'prom_amount' => $prom->prom_amount,
                'avail_from'  => $start->toDateString(),
                'avail_to'    => $end->toDateString(),
                'status'      => $prom->status
            ];
        }

        return response()->json([
            'status'    => true,
            'msg'       => 'Okay',
            'plan'      => $planData
        ]);
    }

    /**
     * Get All Plans By the Logged in User
     * 
     */
    public function listPlans(){
        $user = Auth::user();
        $plans = CreatorPlan::where('user_id', $user->id)
        ->orderBy('id', 'desc')
        ->get();

        $planArray = [];
        foreach ($plans as $k => $p) {
            $planArray[$k] = [
                'id'    => $p->id,
                'uid'   => encrypt($p->id),
                'title' => $p->title,
                'amount' => $p->amount,
                'benefits' => empty($p->benefits) ? '' : implode("\n", $p->benefits),
                'month_2'=> $p->month_2,
                'month_3'=> $p->month_3,
                'month_6'=> $p->month_6,
                'status' => $p->status,
                'create' => $p->created_at->diffForHumans(),
                'update' => empty($p->updated_at) ? '' : $p->updated_at->diffForHumans()
            ];
            $isPromotionActive = false;
            $proms = [];
            foreach ($p->promotions as $i => $pr) {
                if(!$isPromotionActive AND $pr->status){
                    $isPromotionActive = true;
                }
                $proms[$i] = [
                    'uid'   => encrypt($pr->id),
                    'amount'=> $pr->prom_amount,
                    'start' => Carbon::parse($pr->avail_from)->format('d-m-Y'),
                    'end' => Carbon::parse($pr->avail_to)->format('d-m-Y'),
                    'status' => $pr->status
                ];
            }
            $planArray[$k]['prom_active'] = $isPromotionActive;
            $planArray[$k]['prom_exist'] = count($proms);
            $planArray[$k]['promotions'] = $proms;
        }

        return response()->json([
            'status' => true,
            'msg'    => 'Okay',
            'plans'  => $planArray
        ]);
    }

    /**
     * Get Creator's Plan listing
     * 
     * @param string $id Encrypted user id
     * @return \Illuminate\Http\Response json response
     */
    public function listCreatorPlans($id){
        $id = decrypt($id);
        $user = User::findOrFail($id);
        $plans = CreatorPlan::where('user_id', $user->id)
        ->whereStatus(1)
        ->orderBy('id', 'asc')
        ->get();

        $planArray = [];
        foreach ($plans as $k => $p) {
            $planArray[$k] = [
                'id'    => $p->id,
                'uid'   => encrypt($p->id),
                'title' => $p->title,
                'amount' => $p->amount,
                'benefits' => empty($p->benefits) ? '' : implode("\n", $p->benefits),
                'month_2'=> $p->month_2,
                'month_3'=> $p->month_3,
                'month_6'=> $p->month_6,
            ];
            $isPromotionActive = false;
            $proms = [];
            foreach ($p->promotions as $i => $pr) {
                if(!$isPromotionActive AND $pr->status){
                    $isPromotionActive = true;
                }
                if($pr->status){
                    $proms[$i] = [
                        'uid'   => encrypt($pr->id),
                        'amount'=> $pr->prom_amount,
                        'start' => Carbon::parse($pr->avail_from)->format('d-m-Y'),
                        'end' => Carbon::parse($pr->avail_to)->format('d-m-Y'),
                    ];
                }
            }
            $planArray[$k]['prom_active'] = $isPromotionActive;
            $planArray[$k]['prom_exist'] = count($proms);
            $planArray[$k]['promotions'] = $proms;
        }

        return response()->json([
            'status' => true,
            'msg'    => 'Okay',
            'plans'  => $planArray
        ]);
    }

    /**
     * Update Plan Data
     * 
     * @param string $id Encrypted Plan Id
     * @return \Illuminate\Http\Response
     */
    public function updatePlan(CreatorPlanRequest $creatorPlanRequest, $id){
        $id = decrypt($id);
        $user = Auth::user();
        $user_id = $user->id;

        $plan = CreatorPlan::where('id', $id)
        ->where('user_id', $user_id)
        ->first();

        if($plan){

            $data = $creatorPlanRequest->safe()->only([
                'title',
                'amount',
                'benefits',
                'month_2',
                'month_3',
                'month_6',
                'status'
            ]);

            // return response()->json($data, 200);

            $plan->title = $data['title'];
            $plan->amount= $data['amount'];
            $plan->benefits = empty($data['benefits'])? [] : explode("\n", $data['benefits']);
            $plan->month_2 = $data['month_2'] ?? NULL;
            $plan->month_3 = $data['month_3'] ?? NULL;
            $plan->month_6 = $data['month_6'] ?? NULL;
            $plan->status = $data['status'];
            $plan->save();

            $resp = [
                'status'    => true,
                'msg'       => 'Plan Updated Successfully',
                'plan'      => encrypt($plan->id)
            ];

        } else {
            $resp = [
                'status' => false,
                'msg'    => 'Subscription plan not found!'
            ];
        }

        return response()->json($resp, 200);
    }

    /**
     * Create Plan Promotion
     * 
     * @param \App\Http\Requests\PlanPromotionRequest
     * @return \Illuminate\Http\Response
     */
    public function createPromotion(PlanPromotionRequest $planPromotionRequest){
        $data = $planPromotionRequest->only([
            'planId',
            'prom_amount',
            'avail_from',
            'avail_to',
            'status',
        ]);

        $user = Auth::user();
        $user_id = $user->id;

        $prom = new PlanPromotion();
        $prom->user_id = $user_id;
        $prom->plan_id = $data['planId'];
        $prom->prom_amount = $data['prom_amount'];
        $prom->avail_from = Carbon::parse($data['avail_from'].' 00:00:00');
        $prom->avail_to = Carbon::parse($data['avail_to'].' 23:59:59');
        $prom->save();

        return response()->json([
            'status'    => true,
            'msg'       => 'Okay',
            'prom'      => encrypt($prom->id)
        ], 200);
    }

    /**
     * Update Promotions Status
     * @param string $id Encrypted Promotion Id
     *  @return \Illuminate\Http\Response
     */
    public function promotionStatus($id){
        $user = Auth::user();
        $user_id = $user->id;
        $prom = PlanPromotion::where('user_id', $user_id)
        ->where('id', decrypt($id))
        ->first();

        $prom->status = $prom->status ? 0 : 1;
        $prom->save();

        return response()->json([
            'status' => true,
            'msg'    => 'Promotion has been '.$prom->status ? 'Enabled' : 'Disabled'
        ], 200);
    }

    /**
     * Update Plan Promotion
     * 
     * @param \App\Http\Requests\PlanPromotionRequest
     * @param string $id Encrypted Promotion Id
     * @return \Illuminate\Http\Response
     */
    public function updatePromotion(PlanPromotionRequest $planPromotionRequest, $id){
        $data = $planPromotionRequest->only([
            'planId',
            'prom_amount',
            'avail_from',
            'avail_to',
            'status',
        ]);

        $user = Auth::user();
        $user_id = $user->id;
        $id = decrypt($id);

        $prom = PlanPromotion::where('id', $id)
        ->where('user_id', $user_id)
        ->where('plan_id', $data['planId'])
        ->first();
        if($prom){

            $prom->prom_amount = $data['prom_amount'];
            $prom->avail_from = Carbon::parse($data['avail_from'].' 00:00:00');
            $prom->avail_to = Carbon::parse($data['avail_to'].' 23:59:59');
            // $prom->status = $data['status'];
            $prom->save();

            return response()->json([
                'status'    => true,
                'msg'       => 'Promotion updated successfully',
                'prom'      => encrypt($prom->id)
            ], 200);

        } else {
            return response()->json([
                'status'    => false,
                'message'       => 'Promotion not found',
            ], 404);
        }
    }

    /**
     * Get Promotions Details
     * 
     * @param string $id Encrypted Promotions Id
     * @return \Illuminate\Http\Response
     */
    public function getPromotion($id){
        $user = Auth::user();
        $user_id = $user->id;
        $id = decrypt($id);
        $prom = PlanPromotion::where('id', $id)
        ->where('user_id', $user_id)
        ->first();
        if($prom){
            return response()->json([
                'id' => $prom->id,
                'uid'=> encrypt($prom->id),
                'prom_amount' => $prom->prom_amount,
                'avail_from' => Carbon::parse($prom->avail_from)->format('d-m-Y'),
                'avail_to' => Carbon::parse($prom->avail_to)->format('d-m-Y'),
                'created' => $prom->created_at->diffForHumans(),
                'updated' => $prom->updated_at->diffForHumans(),
            ], 200);
        } else {
            return response()->json([
                'message' => 'Promotion Not Found!'
            ], 404);
        }
    }

    /**
     * Get Plan Information
     * While Subscribing
     * 
     * @param string $id Plan's encrypted Id
     * @return \Illuminate\Http\Response json response
     */
    public function getPlanDetails($id){
        // $planId = $id;
        $planId = decrypt($id);
        $plan = CreatorPlan::findOrFail($planId);

        $planData = [
            'title' => $plan->title,
            'amount' => $plan->amount,
            'benefits' => $plan->benefits,
            'month_2' => $plan->month_2,
            'month_3' => $plan->month_3,
            'month_6' => $plan->month_6,
            'promotion' => $plan->latestActivePromotion()
        ];

        return response()->json([
            'plan' => $planData
        ]);
    }
}
