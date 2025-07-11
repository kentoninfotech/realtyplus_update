<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_milestones extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'id', 'business_id');
    }

    public function project()
    {
        return $this->belongsTo(projects::class, 'project_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(tasks::class, 'milestone_id', 'id');
    }

    public function assignedTo(){
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
}
