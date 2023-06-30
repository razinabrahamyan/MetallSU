<?php

namespace App\Providers;


use App\Models\ItemStorage;
use App\Models\Position;
use App\Models\ResponsibleType;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
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
        View::composer('*', function ($view){
            if(auth()->check()){
                if(auth()->user()->role_id ===1){

                    $positions = Position::where('user_id',auth()->id())->get();
                    $lists = ResponsibleType::where('user_id',auth()->id())->get();
                    $view->with([
                        'sidebar_positions' => $positions,
                        'sidebar_lists' => $lists
                    ]);
                }else{
                    $storages = ItemStorage::where('user_id',auth()->id())->get();
                    $view->with([
                        'storages' => $storages
                    ]);
                }

            }

        });
    }
}
