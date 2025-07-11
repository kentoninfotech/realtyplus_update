<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materials extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function stock()
    {
        return $this->hasOne(material_stock::class, 'material_id', 'id');
    }
}
