<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class projects extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'id', 'business_id');
    }

    public function milestones()
    {
        return $this->hasMany(project_milestones::class, 'project_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(tasks::class, 'project_id', 'id');
    }

    public function project_files()
    {
        return $this->hasMany(project_files::class, 'project_id', 'id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id', 'id');
    }
}
