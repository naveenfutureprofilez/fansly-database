<?php

namespace Modules\Super\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\CreatorRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\UserNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Modules\Super\Http\Requests\AdminLoginRequest;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function login()
    {   
        $title = "Admin Login";
        return view('super::login',[
            'title' => $title
        ]);
    }

    /**
     * Display a listing of the resource.
     * 
     * @param Modules\Super\Http\Requests\AdminLoginRequest $adminLoginRequest
     * @return void
     */
    public function verify(AdminLoginRequest $adminLoginRequest)
    {
        $data = $adminLoginRequest->only('ausername', 'apassword');
        $logData = [
            'isAdmin' => 'Yes'
        ];
        $data['isAdmin'] = 'Yes';
        if(!filter_var($data['ausername'], FILTER_VALIDATE_EMAIL)){
            $logData['username'] = $data['ausername'];
        } else {
            $logData['email'] = $data['ausername'];
        }
        $logData['password'] = $data['apassword'];
        
        if (Auth::attempt($logData)) {
            $user = Auth::user();
            return redirect(route('super.dashboard'))->with('success', 'Welcome Back '.$user->name);
        } else {
            return redirect(route('super.login'))->with('error', 'Invalid Credentials!');
        }
    }
    

    /**
     * Admin View user details
     * 
     * @param \App\Models\User $user
     * @return Renderable
     */
    public function viewUser(User $user){

        return view('super::users.view', [
            'title' => $user->name,
            'user'  => $user
        ]);

    }

    /**
     * List All the users
     * @return Renderable
     */
    public function listUsers(){
        $admin = Auth::user();
        $users = User::where('id' ,'!=', $admin->id)
        ->where('isAdmin', 'No')
        ->where('role', 0)
        ->orderBy('id', 'desc')
        ->get();

        return view('super::users.list',[
            'title' => 'All users',
            'users' => $users
        ]);
    }

    /**
     * List All the Creators Only
     * @return Renderable
     */
    public function listCreators(){
        $admin = Auth::user();
        $users = User::where('id' ,'!=', $admin->id)
        ->where('isAdmin', 'No')
        ->where('role', 1)
        ->where('is_pro', 0)
        ->orderBy('id', 'desc')
        ->get();

        return view('super::users.list',[
            'title' => 'All Creators',
            'users' => $users
        ]);
    }
    /**
     * List All the Creators Only
     * @return Renderable
     */
    public function listCreatorsPro(){
        $admin = Auth::user();
        $users = User::where('id' ,'!=', $admin->id)
        ->where('isAdmin', 'No')
        ->where('role', 1)
        ->where('is_pro', 1)
        ->orderBy('id', 'desc')
        ->get();

        return view('super::users.list',[
            'title' => 'Pro Creators',
            'users' => $users
        ]);
    }

    /**
     * List All Creator Requests
     * @return Renderable
     */
    public function listCreatorRequests(){
        // $admin = Auth::user();
        $requests = CreatorRequest::whereIn('status', [0,1,3])
        ->with('user')
        ->orderby('updated_at', 'desc')
        ->get();

        return view('super::users.requests',[
            'title' => 'All Creator Requests',
            'requests' => $requests
        ]);
    }

    /**
     * List Approved Creators Request
     * @return Renderable
     */
    public function listApprovedRequests(){

        $requests = CreatorRequest::whereIn('status', [2])
        ->with('user')->safe()
        ->orderby('updated_at', 'desc')
        ->get();

        return view('super::users.requests',[
            'title' => 'Approved Creator Requests',
            'requests' => $requests
        ]);
    }

    /**
     * List Rejected Creators Request
     * @return Renderable
     */
    public function listRejectedRequests(){
        
        $requests = CreatorRequest::whereIn('status', [3])
        ->with('user')
        ->orderby('updated_at', 'desc')
        ->get();

        return view('super::users.requests',[
            'title' => 'Rejected Creator Requests',
            'requests' => $requests
        ]);
    }

    /**
     * View Creator Request
     * @param \App\Models\CreatorRequest
     * @return Renderable
     */
    public function creatorRequest(CreatorRequest $creatorRequest){
        return view('super::users.request',[
            'title' => 'Creator Request Update',
            'request' => $creatorRequest
        ]);
    }

    /**
     * Approve Creator Request
     * @param \App\Models\CreatorRequest $creatorRequest
     * @param Illuminate\Http\Request $request 
     * @return void
     */
    public function creatorRequestApprove(CreatorRequest $creatorRequest, Request $request){
        if($creatorRequest->status != 2){
            $admin = Auth::user();

            $creatorRequest->status = 2;
            $creatorRequest->approved_at = Carbon::now();
            $creatorRequest->approved_by = $admin->id;
            $creatorRequest->save();

            $user = User::find($creatorRequest->user_id);
            $user->role = 1;
            $user->save();

            UserNotification::requestApproved($creatorRequest);

            return redirect(route('super.requests'))->withSuccess('Requests Approved!');
        } else {
            return redirect(route('super.requests'))->withError('Requests already approved!');
        }
    }

    /**
     * Reject Creator Request
     * @param \App\Models\CreatorRequest $creatorRequest
     * @param Illuminate\Http\Request $request 
     * @return void
     */
    public function creatorRequestReject(CreatorRequest $creatorRequest, Request $request){
        if($creatorRequest->status != 3){
            $admin = Auth::user();

            $creatorRequest->status = 3;
            $creatorRequest->approved_at = Carbon::now();
            $creatorRequest->approved_by = $admin->id;
            $creatorRequest->save();

            $user = User::find($creatorRequest->user_id);
            $user->role = 0;
            $user->save();

            return redirect(route('super.requests'))->withSuccess('Requests Rejected!');
        } else {
            return redirect(route('super.requests'))->withError('Requests already rejected!');
        }
    }

    /**
     * Reject Creator Request
     * @param \App\Models\CreatorRequest $creatorRequest
     * @param Illuminate\Http\Request $request 
     * @return void
     */
    public function creatorRequestIncomplete(CreatorRequest $creatorRequest, Request $request){
        if($creatorRequest->status != 1){
            $admin = Auth::user();

            $validator = Validator::make($request->all(),[
                'remark' => 'required'
            ], [
                'remark.required' => 'Please write a remark for incomplete request.'
            ]);
            if($validator->fails()){
                return redirect(route('super.request',['creatorRequest' => $creatorRequest->id]))
                        ->withErrors($validator)
                        ->withInput();
            }

            $data = $validator->safe()->only(['remark']);
            $creatorRequest->status = 1;
            $creatorRequest->remark = $data['remark'];
            // $creatorRequest->approved_at = Carbon::now();
            $creatorRequest->approved_by = $admin->id;
            $creatorRequest->save();

            // $user = User::find($creatorRequest->user_id);
            // $user->role = 0;
            // $user->save();
            UserNotification::needRequestUpdate($creatorRequest);
            
            return redirect(route('super.requests'))->withSuccess('An update request has been sent to the user!');
        } else {
            return redirect(route('super.requests'))->withError('Already a need update request sent!');
        }
    }

    /**
     * Admin Block a user
     * 
     * @param \App\Models\User $user
     * @return void
     */
    public function blockUser(User $user){
        $user->status = 2;
        $user->save();
        return redirect()->back()->withError('User has been blocked');
    }

    public function logout(){
        Auth::guard('web')->logout();
        return redirect(route('super.login'))->withSuccess('Logged out successfully.');
    }

}
