<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; 
use App\Models\LeavePermit;         
use Illuminate\Support\Facades\Auth; 

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        
    }

  
    
    public function boot(): void
    {
        
  View::composer('inc.header', function ($view) {
        
        if (Auth::check()) {
            $user = Auth::user();

            
            if ($user->role === 'pimpinan') {
                $pendingLeaves = \App\Models\LeavePermit::with('user')
                    ->where('status', 'Pending')
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                $view->with('pendingLeaves', $pendingLeaves);
            } else {
                
                $view->with('pendingLeaves', null);
            }
        }
    });
    }
}