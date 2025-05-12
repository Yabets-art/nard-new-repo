<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Alert;


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
    View::composer('admin.partials.topbar', function ($view) {
        $recentMessages = Message::latest()->take(4)->get();
        $view->with('recentMessages', $recentMessages);
        $recentAlerts = Alert::latest()->take(4)->get();
$view->with('recentAlerts', $recentAlerts);
    });}
    

};

