<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'taskable_type', 
        'taskable_id', 
        'title', '
        description',
        'assigned_to_user_id', 
        'due_date', 
        'status', 
        'priority',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function taskable()
    {
        return $this->morphTo();
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
