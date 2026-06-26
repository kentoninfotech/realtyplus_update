<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyInterest extends Model
{
    use HasFactory;

    protected $table = 'property_interests';

    protected $fillable = [
        'property_id',
        'name',
        'email',
        'phone',
        'interest_type',
        'message',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
