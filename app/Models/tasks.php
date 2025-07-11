<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tasks extends Model
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

    public function milestone()
    {
        return $this->belongsTo(project_milestones::class, 'milestone_id', 'id');
    }

    public function reports()
    {
        return $this->hasMany(milestone_reports::class, 'task_id', 'id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }

    public function workers()
    {
        return $this->hasMany(task_workers::class, 'task_id', 'id');
    }

    public function materialsUsed()
    {
        return $this->hasMany(material_checkouts::class, 'task_id', 'id');
    }
    
}
