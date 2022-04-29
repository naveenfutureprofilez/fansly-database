<?php

namespace Modules\Super\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Subscription;
use App\Models\Tip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('super::dashboard',[
            'title' => 'Admin Dashboard',
            'creators' => User::where('role', 1)->where('isAdmin', '!=', 'Yes')->where('is_pro',0)->count(),
            'pro' => User::where('role', 1)->where('isAdmin', '!=', 'Yes')->where('is_pro',1)->count(),
            'fans'  => User::where('role', 0)->where('isAdmin', '!=', 'Yes')->count(),
            'subscriptions' => Subscription::where('status', 1)->count(),
            'default' => User::where('pre_type', 'Default')->count(),
            'fan' => User::where('pre_type', 'fan')->count(),
            'creator' => User::where('pre_type', 'creator')->count(),
            'creatorPro' => User::where('pre_type', 'creator-pro')->count(),
            'totalTips' => Tip::where('status', 1)->sum('amount'),
            'monthEarnings' => PaymentTransaction::where('status', 1)->whereMonth('created_at', Carbon::now()->month)->sum('tax'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('super::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('super::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('super::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
