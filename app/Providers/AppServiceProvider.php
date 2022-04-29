<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('super::_parts.header', function($view){
            $user = Auth::user();
            $view->with([
                'messages' => Message::where('to_id', $user->id)->where('is_read',0)->orderBy('id','desc')->get(),
                'notifications' => Notification::where('notifiable_type', 'admin')->whereNull('read_at')->get()
            ]);
        });
        view()->composer('super::_parts.sidebar', function($view){
            $view->with([
                'ttlReported' => Post::where('reported',1)->count(),
                'ttlBlocked' => Post::where('status',4)->count(),
            ]);
        });

        // To create whereLike conditions
        Builder::macro('whereLike', function(string $column, string $search) {
            return $this->where($column, 'LIKE', '%'.$search.'%');
        });
    }
}
