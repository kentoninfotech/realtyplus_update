<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'description',
        'slug',
        'is_residential',
        'can_have_multiple_units'
    ];


    public function properties()
    {
        return $this->hasMany(Property::class);
    }

}
