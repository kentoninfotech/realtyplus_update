<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\accountheads' => 'App\Policies\AccountheadsPolicy',
        'App\Models\material_checkouts' => 'App\Policies\MaterialCheckoutsPolicy',
        'App\Models\material_stock' => 'App\Policies\MaterialStockPolicy',
        'App\Models\material_supplies' => 'App\Policies\MaterialSuppliesPolicy',
        'App\Models\materials' => 'App\Policies\MaterialsPolicy',
        'App\Models\projects' => 'App\Policies\ProjectsPolicy',
        'App\Models\suppliers' => 'App\Policies\SuppliersPolicy',
        'App\Models\tasks' => 'App\Policies\TasksPolicy',
        'App\Models\transactions' => 'App\Policies\TransactionsPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy',
        'App\Models\Client' => 'App\Policies\ClientPolicy',
        // 'App\Models\Business' => 'App\Policies\BusinessPolicy',
        // 'App\Models\businessgroups' => 'App\Policies\BusinessgroupPolicy',
        // 'App\Models\categories' => 'App\Policies\CategoryPolicy',

        // 'App\Models\payments' => 'App\Policies\PaymentPolicy',
        // 'App\Models\project_files' => 'App\Policies\ProjectFilePolicy',
        // 'App\Models\progect_milestones' => 'App\Policies\ProgectMilestonePolicy',
    ];


    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permission checks using can()
        Gate::before(function ($user, $ability) {
                 if ($user->hasRole('Super Admin')) {
               return true;           }
        });
    }
}
