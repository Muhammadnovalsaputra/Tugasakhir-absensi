<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\LeavePermit;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('inc.header', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                if ($user->role === 'Pimpinan' || $user->role === 'pimpinan' || $user->role === 'Admin') {
                    $pendingLeaves = LeavePermit::with('user')
                        ->where('status', 'Pending')
                        ->orderBy('created_at', 'desc')
                        ->get();
                    
                    $view->with('pendingLeaves', $pendingLeaves);
                } else {
                    $view->with('pendingLeaves', collect());
                }
            } else {
                $view->with('pendingLeaves', collect());
            }
        });
    }
}