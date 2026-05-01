<?php

namespace App\Models;

use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'description',
        'slug',
        'is_residential',
        'can_have_multiple_units'
    ];

    /**
     * Boot model: auto-fill business_id for tenants (super admins create
     * shared/global types with business_id = null), and apply a tenant scope
     * that allows each user to see their own types plus shared global types.
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check() && empty($model->business_id)) {
                $user = Auth::user();
                if (empty($user->is_super_admin) && ! empty($user->business_id)) {
                    $model->business_id = $user->business_id;
                }
            }
        });

        static::addGlobalScope('businessOrShared', function ($builder) {
            if (! Auth::check()) {
                return;
            }

            $user = Auth::user();

            // Super admins see everything.
            if (! empty($user->is_super_admin)) {
                return;
            }

            $table = $builder->getModel()->getTable();

            // Tenants see global (null) types AND their own business's types.
            $builder->where(function ($q) use ($table, $user) {
                $q->whereNull($table . '.business_id');
                if (! empty($user->business_id)) {
                    $q->orWhere($table . '.business_id', $user->business_id);
                }
            });
        });
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
