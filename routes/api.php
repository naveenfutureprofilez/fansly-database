<?php
namespace App\Http\Controllers\Api;

use App\EmailService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Modules\Super\Http\Controllers\PostController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/check', function(){
    EmailService::welcome([
        'to' => 'pradeep@fpdemo.com',
        'name' => 'Pradeep'
    ]);
    return response()->json([
        'status' => 200,
        'msg'    => 'Okay'
    ]);
});

Route::get('watermark', [FilesController::class, 'addWatermark']);
// Route::get('get-yoti-session', [CreatorController::class, 'getYotiSession']);
// Route::get('check-yoti-session', [CreatorController::class, 'getYotiSession']);

Route::prefix('settings')->group(function(){
    Route::get('/', [SettingsController::class, 'seoSettings']);
});
Route::post('upload-file-s3', [FilesController::class, 'uploadAtS3']);
Route::post('/login', [UsersController::class, 'login']);
Route::post('/register', [UsersController::class, 'register']);
Route::post('/check-username', [UsersController::class, 'checkUsername']);
// Route::get('/profile/{id}', [UsersController::class, 'userProfile']);
// Route::get('/user/profile/{id}', [UsersController::class, 'userProfileData']);
// Route::get('/user/{id}', [UsersController::class, 'viewUser']);

Route::get('/user/stripe', [UsersController::class, 'updateStripeCustom']);
// Route::get('/user/wallet', [UsersController::class, 'walletBalance']);
// Route::get('/requests', [UsersController::class, 'pendingRequests']);
// Route::post('/creator-request', [UsersController::class, 'creatorRequest']);
// Route::post('/upload-file', [FilesController::class, 'uploadTempFile']);
// Route::post('/upload-multi-file', [FilesController::class, 'uploadMultiFiles']);
// Route::get('/delete-temp-file/{file}', [FilesController::class, 'deleteFile']);
Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('user')->group(function(){
        Route::get('/', function(Request $request){
            $user = User::find(Auth::user()->id);
            return response()->json([
                'uid' => encrypt($user->id),
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'bio'   => $user->bio,
                'avatar' => $user->avatar,
                'banner' => $user->banner,
                'is_pro' => $user->is_pro,
                'role' => $user->role,
                'likes' => $user->likes(),
                'followers' => $user->followers(),
                'videos' => User::totalVideos($user->id),
                'images' => User::totalImages($user->id)
            ], 200);
        });

        Route::get('/{id}', [UsersController::class, 'viewUser']);
        Route::get('/u/{username}', [UsersController::class, 'viewUserByUsername']);
        Route::post('/logout', [UsersController::class, 'logout']);
        Route::get('/profile/{id}', [UsersController::class, 'userProfileData']);

        //Get Paid Media
        Route::get('purchased-media/{type}/{after?}', [UsersController::class, 'purchasedMedia']);

        // Route::get('/wallet', [UsersController::class, 'walletBalance']);
        // Route::get('/balance', [UsersController::class, 'walletBalance']);
    });
    Route::get('/balance', [UsersController::class, 'walletBalance']);

    Route::post('/search-creators',[UsersController::class, 'searchCreator']);
    Route::post('/search-users',[UsersController::class, 'searchUsers']);

    Route::prefix('notifications')->name('notify.')->group(function(){
        Route::get('/list/{last?}',[NotificationController::class, 'index']);
        Route::get('/read/{id}',[NotificationController::class, 'markAsRead']);
        Route::get('/read-all',[NotificationController::class, 'markAllAsRead']);
        Route::get('/delete-all',[NotificationController::class, 'deleteAll']);
    });

    Route::get('/profile/{id}', [UsersController::class, 'userProfile']);
    Route::get('/requests', [UsersController::class, 'pendingRequests']);
    Route::post('/creator-request', [UsersController::class, 'creatorRequest']);
    Route::post('/creator-request/{id}', [UsersController::class, 'creatorRequestUpdate']);
    Route::get('is-verified', [CreatorController::class, 'isAgeVerified']);
    Route::post('age-verify/{verify?}', [CreatorController::class, 'verifyAge']);

    /**
     * Files Controll
     */
    Route::post('/upload-file', [FilesController::class, 'uploadTempFile']);
    Route::post('/upload-multi-file', [FilesController::class, 'uploadMultiFiles']);
    Route::get('/delete-temp-file/{file}', [FilesController::class, 'deleteFile']);
    
    /**
     * User Data Update
     */
    Route::post('/update-profile-image', [UsersController::class, 'updateProfileImage']);
    Route::post('/update-profile-banner', [UsersController::class, 'updateBannerImage']);
    Route::post('/update-profile', [UsersController::class, 'updateProfileData']);
    Route::post('/update-username', [UsersController::class, 'updateUsername']);
    Route::post('/update-email', [UsersController::class, 'updateEmail']);
    Route::post('/update-password', [UsersController::class, 'updatePassword']);
    Route::get('notify-setting/{status}', [UsersController::class, 'notify']);
    Route::get('notify-status', [UsersController::class, 'notifyStatus']);
    
    /**
     * Creator Routes
     */
    Route::prefix('creator')->name('creator.')->middleware('role:1')->group(function(){
        // Route::post('/create-post', [PostsController::class, 'createPost']);
        Route::post('/create-plan', [CreatorPlansController::class, 'createPlan']);
        Route::get('/get-plan/{plan}', [CreatorPlansController::class, 'getPlan']);
        Route::get('/plans', [CreatorPlansController::class, 'listPlans']);
        Route::post('/plan-update/{id}', [CreatorPlansController::class, 'updatePlan']);
        Route::post('/promotion/create', [CreatorPlansController::class, 'createPromotion']);
        Route::post('/promotion/update/{id}', [CreatorPlansController::class, 'updatePromotion']);
        Route::post('/plan-promotion-status/{id}', [CreatorPlansController::class, 'promotionStatus']);

        // Pro Subscription
        Route::get('/pro-subscription', [PaymentsController::class, 'proSubscription']);
        Route::post('/pro-subscribe', [PaymentsController::class, 'proSubscribe']);
        Route::get('/pro-auto-renew/{id}/{status}', [PaymentsController::class, 'autoRenewUpdate']);
        Route::get('/pro-cancel', [PaymentsController::class, 'cancelProSubscription']);
        Route::get('/pro-renew/{id}', [PaymentsController::class, 'renewProSubscription']);

        //Posts Manage By created
        Route::prefix('post')->name('post.')->group(function(){
            Route::post('/create', [PostsController::class, 'createPost']);
            Route::get('/all/{after?}',[CreatorController::class, 'posts']);
            Route::get('/active/{after?}',[CreatorController::class, 'activePosts']);
            Route::get('/blocked/{after?}',[CreatorController::class, 'blockedPosts']);
            Route::get('/scheduled/{after?}',[CreatorController::class, 'scheduledPosts']);
            Route::get('/archive/{post}',[PostsController::class, 'archivePost']);
            Route::get('/delete/{post}',[PostsController::class, 'deletePost']);
        });

        //Subscriptions
        Route::prefix('subscription')->group(function(){
            Route::get('active/{after?}', [CreatorController::class, 'activeSubscriptions']);
            Route::get('expired/{after?}', [CreatorController::class, 'expiredSubscriptions']);
        });

        //Tips
        Route::get('tips-received/{after?}', [CreatorController::class, 'tips']);

        //Media
        Route::get('media/{type}/{after?}', [CreatorController::class, 'media']);

        // Payout
        // Route::prefix('payout')->group(function(){
            
        // });

        // Watermark
        Route::match(['get', 'post'], 'watermark' ,[CreatorController::class, 'creatorWatermark']);

        //Messages
        Route::prefix('message')->group(function(){
            Route::match(['get','post'], 'settings', [CreatorController::class, 'msgSettings']);
        });

        //Payout Control
        Route::prefix('payout')->name('payout.')->group(function(){
            Route::post('request', [PayoutController::class, 'payoutRequest']);
            Route::get('methods', [PayoutController::class, 'payoutMethods']);
            Route::get('available', [PayoutController::class, 'availablePayout']);
            Route::post('update-paypal', [PayoutController::class, 'updatePaypal']);
            Route::post('update-bank', [PayoutController::class, 'updateBank']);
            Route::get('history', [PayoutController::class, 'payoutHistory']);
        });

    });

    Route::get('/creator-plans/{id}', [CreatorPlansController::class, 'listCreatorPlans']);

    Route::get('/posts/{type}/{after?}',[PostsController::class, 'getPosts']);
    Route::prefix('post')->name('post.')->group(function(){
        Route::get('/{id}',[PostsController::class, 'viewPost']);
        Route::get('/comments/{id}/{last?}',[PostsController::class, 'postComments']);
        Route::get('/like/{id}',[PostsController::class, 'likePost']);
        Route::post('/comment/{id}',[PostsController::class, 'comment']);
        Route::post('/report/{id}',[PostsController::class, 'reportPost']);

    });

    //Payments
    Route::prefix('payment')->name('payment.')->group(function(){
        Route::post('/add-new', [PaymentsController::class, 'createPaymentMethod']);
        Route::get('/methods', [PaymentsController::class, 'listPaymentMethods']);
        Route::get('/default-set/{id}', [PaymentsController::class, 'makeDefault']);
        Route::get('/delete/{id}', [PaymentsController::class, 'deletePaymentMethod']);

        Route::post('/wallet-topup',[PaymentsController::class, 'walletTopup']);
        Route::get('/transactions/{after?}', [PaymentsController::class, 'paymentTrans']);
        Route::get('/wallet-history/{after?}', [PaymentsController::class, 'walletHistory']);
    });

    //Tips
    Route::prefix('tip')->name('tip.')->group(function(){
        Route::get('/list', [TipsController::class, 'listTips']);
        Route::post('/direct/{id}', [TipsController::class, 'directTip']);
        Route::post('/post/{post}', [TipsController::class, 'postTip']);
        Route::post('/msg/{msg}', [TipsController::class, 'messageTip']);
    });

    //Follow-unfollow
    Route::get('/follow-suggestions',[UsersController::class, 'usersToFollow']);
    Route::get('/follow/{id}',[UsersController::class, 'follow']);
    Route::get('/unfollow/{id}',[UsersController::class, 'unfollow']);

    // Get Subscription Plan for subscribe
    Route::get('/plan-details/{id}', [CreatorPlansController::class, 'getPlanDetails']);

    //Subscribe to a creators plan
    Route::get('subscribe/{id}/{month?}', [PaymentsController::class, 'subscribe']);

    // Purchase a Post Content
    Route::post('purchase-post/{id}', [PostsController::class, 'postPurchase']);

    

    // Subscriptions
    Route::prefix('subscription')->group(function(){
        Route::get('active', [PaymentsController::class, 'activeSubscriptions']);
        Route::get('expired', [PaymentsController::class, 'expiredSubscriptions']);
    });

    // Conversations
    Route::prefix('message')->group(function(){
        Route::post('send/{id}', [MessagesController::class, 'sendMessage']);
        Route::post('purchase/{id}', [MessagesController::class, 'purchaseMessageMedia']);
        Route::get('conversations', [MessagesController::class, 'conversations']);
        Route::get('chat/{id}', [MessagesController::class, 'msgs']);
        Route::get('load-more/{c}/{m}', [MessagesController::class, 'loadMore']);
        Route::get('recent/{c}/{last}', [MessagesController::class, 'recentMessage']);
        Route::get('read-status/{msg}', [MessagesController::class, 'readStatus']);
        Route::get('unread-count', [MessagesController::class, 'unreadCount']);
    });

    // Yoti Age Verification
    Route::get('get-yoti-session', [CreatorController::class, 'getYotiSession']);
    Route::get('check-yoti-session/{session}', [CreatorController::class, 'checkYotiSession']);

});

// Route::get('/plan-details/{id}', [CreatorPlansController::class, 'getPlanDetails']);
/**
 * Creator Routes
 */
Route::prefix('creator')->name('creator.')->group(function(){
    Route::prefix('post')->name('post.')->group(function(){
        // Route::post('/create', [PostsController::class, 'createPost']);
    });
    // Route::post('/create-plan', [CreatorPlansController::class, 'createPlan']);
    // Route::get('/get-plan/{plan}', [CreatorPlansController::class, 'getPlan']);
    // Route::get('/plans', [CreatorPlansController::class, 'listPlans']);
    // Route::post('/plan-update/{id}', [CreatorPlansController::class, 'updatePlan']);
    Route::post('/promotion/create', [CreatorPlansController::class, 'createPromotion']);
    Route::post('/promotion-update/{id}', [CreatorPlansController::class, 'updatePromotion']);
    // Route::match(['get', 'post'], 'watermark' ,[CreatorController::class, 'creatorWatermark']);
});
// Route::post('/update-profile-image', [UsersController::class, 'updateProfileImage']);
// Route::post('/update-profile-banner', [UsersController::class, 'updateBannerImage']);
// Route::post('/update-profile', [UsersController::class, 'updateProfileData']);
// Route::get('/follow/{id}',[UsersController::class, 'follow']);
// Route::get('/unfollow/{id}',[UsersController::class, 'unfollow']);
// Route::get('/posts/{type}/{after?}',[PostsController::class, 'getPosts']);
// Route::get('/post-data/{id}',[PostsController::class, 'getPostStats']);
// Route::get('/post/like/{id}',[PostsController::class, 'likePost']);

Route::prefix('post')->name('post.')->group(function(){
    // Route::get('/{id}',[PostsController::class, 'viewPost']);
    // Route::get('/comments/{id}/{last?}',[PostsController::class, 'postComments']);
    // Route::get('/like/{id}',[PostsController::class, 'likePost']);
    // Route::post('/comment/{id}',[PostsController::class, 'comment']);
    // Route::post('/report/{id}',[PostsController::class, 'reportPost']);
});

Route::prefix('payout')->name('payout.')->group(function(){
    // Route::get('methods', [PayoutController::class, 'payoutMethods']);
    // Route::post('update-paypal', [PayoutController::class, 'updatePaypal']);
    // Route::post('update-bank', [PayoutController::class, 'updateBank']);
});


// Route::get('/follow-suggestions',[UsersController::class, 'usersToFollow']);
// Route::post('/payment/wallet-topup',[PaymentsController::class, 'walletTopup']);
// Route::get('/payment/methods', [PaymentsController::class, 'listPaymentMethods']);
// Route::get('/payment/transactions', [PaymentsController::class, 'paymentTrans']);

// Route::post('/search-creators',[UsersController::class, 'searchCreator']);
Route::prefix('message')->group(function(){
    // Route::post('send/{id}', [MessagesController::class, 'sendMessage']);
    // Route::get('chat/{id}', [MessagesController::class, 'msgs']);
    // Route::get('load-more/{c}/{m}', [MessagesController::class, 'loadMore']);
    // Route::match(['get','post'], 'settings', [CreatorController::class, 'msgSettings']);
});
Route::prefix('notifications')->name('notify.')->group(function(){
    // Route::get('/list/{last?}',[NotificationController::class, 'index']);
    // Route::get('/read/{id}',[NotificationController::class, 'markAsRead']);
    // Route::get('/read-all',[NotificationController::class, 'markAllAsRead']);
    // Route::get('/delete-all',[NotificationController::class, 'deleteAll']);
});