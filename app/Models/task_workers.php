<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;

class task_workers extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function task(){
        return $this->belongsTo(tasks::class, 'task_id', 'id');
    }

    public function worker(){
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }


}
