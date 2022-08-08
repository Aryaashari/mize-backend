<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Priority;
use App\Models\Size;
use App\Policies\PriorityPolicy;
use App\Policies\SizePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Size::class => SizePolicy::class,
        Priority::class => PriorityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 
    }
}
