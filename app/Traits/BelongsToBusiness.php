<?php

namespace App\Traits;

use App\Models\Business;
use App\Scopes\BusinessScope;
use Illuminate\Support\Facades\Auth;

/**
 * Apply tenant scoping to any Eloquent model with a `business_id` column.
 *
 * - Auto-fills business_id on create from the authenticated user.
 * - Adds a global query scope to restrict reads to the user's business.
 * - Provides a `business()` relationship.
 */
trait BelongsToBusiness
{
    public static function bootBelongsToBusiness(): void
    {
        static::addGlobalScope(new BusinessScope);

        static::creating(function ($model) {
            if (empty($model->business_id) && Auth::check()) {
                $user = Auth::user();
                if (! empty($user->business_id)) {
                    $model->business_id = $user->business_id;
                }
            }
        });
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
