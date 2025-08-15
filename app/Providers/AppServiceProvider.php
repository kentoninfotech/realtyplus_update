<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\tasks;
use App\Models\User;
use App\Models\Business;
use App\Models\businessgroups;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Relation;
// use Auth;

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
        // View composer (variable that becomes available in all blade view)
        view()->composer('*',function($view) {

            if (Auth::check())
            {
                $businessId = Auth::user()->business_id;
                $view->with('login_user', Auth::user());
                $view->with('mytasks', tasks::where('assigned_to', Auth::user()->id)->get());
                $view->with('clients', User::select('id','name','name','status')->where('business_id', $businessId)->where('user_type', 'client')->get());
                $view->with('staff', User::select('id','name','phone_number','status')
                       ->where('business_id', $businessId)
                    //    ->whereNotIn('user_type', ['client', 'supplier', 'labourer'])
                       ->whereIn('user_type', ['admin', 'worker', 'staff', 'contractor'])
                       ->get());

                $view->with('userbusinesses',Business::select('id','business_name')->where('user_id', Auth::user()->id)->orWhere('id', $businessId)->get());

                $view->with('business', Business::where('id', $businessId)->first());

                $view->with('businesses', Business::where('id', $businessId)->first());

            }else{
                $view->with('business', Business::first());
            }

            $view->with('businessgroups',businessgroups::select('id','businessgroup_name')->get());

            // if you need to access in controller and views:
            // Config::set('something', $something);
        });
    }
}
