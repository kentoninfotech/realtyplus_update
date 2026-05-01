<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function businessgroup()
    {
        return $this->belongsTo(businessgroups::class, 'id', 'businessgroup_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function materials()
    {
        return $this->hasMany(materials::class, 'business_id', 'id');
    }

    public function suppliers()
    {
        return $this->hasMany(suppliers::class, 'business_id', 'id');
    }

    public function personnel()
    {
        return $this->hasMany(User::class, 'business_id', 'id');
    }

    public function projects()
    {
        return $this->hasMany(projects::class, 'business_id', 'id');
    }

    public function milestones()
    {
        return $this->hasMany(project_milestones::class, 'business_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(tasks::class, 'business_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function plan()
    {
        return $this->hasOneThrough(
            Plan::class,
            Subscription::class,
            'business_id',
            'id',
            'id',
            'plan_id'
        );
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}
