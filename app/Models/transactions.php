<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactions extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function project()
    {
        return $this->belongsTo(projects::class, 'project_id', 'id');
    }
}
