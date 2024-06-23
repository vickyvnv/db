<?php

// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\DbiRequest;
use App\Policies\DbiRequestPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        DbiRequest::class => DbiRequestPolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];
    
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Listen for the Logout event
        Event::listen(Logout::class, function ($user) {
            // Clear the user's session data
            Session::flush();

            // Perform any additional logout actions if needed
            // For example, redirect the user to the login page
            return redirect('/login');
        });
    }
}
