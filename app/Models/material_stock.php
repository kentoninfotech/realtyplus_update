<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;

class material_stock extends Model
{
    use HasFactory, BelongsToBusiness;

    protected $guarded = [];

    public function business()
    {
        return $this->belongsTo(Business::class, 'id', 'business_id');
    }
}
