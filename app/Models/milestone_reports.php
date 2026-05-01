<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;

class milestone_reports extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'id', 'business_id');
    }

    public function task()
    {
        return $this->belongsTo(tasks::class, 'task_id', 'id');
    }

    public function milestone()
    {
        return $this->belongsTo(project_milestones::class, 'milestone_id', 'id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by', 'id');
    }

}
