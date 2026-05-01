<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

/**
 * Global scope that restricts queries to the currently authenticated
 * user's business_id, providing strong multi-tenant data isolation.
 *
 * Bypassed when:
 *  - There is no authenticated user (e.g. CLI / public landing forms).
 *  - The authenticated user is a Super Admin.
 *  - The query explicitly calls ->withoutGlobalScope(BusinessScope::class).
 */
class BusinessScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (! Auth::check()) {
            return;
        }

        $user = Auth::user();

        // Super admins see everything.
        if (! empty($user->is_super_admin)) {
            return;
        }

        if (empty($user->business_id)) {
            // User without a business cannot see tenant data.
            $builder->whereRaw('1 = 0');
            return;
        }

        $builder->where($model->getTable() . '.business_id', $user->business_id);
    }
}
