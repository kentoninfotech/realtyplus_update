<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToBusiness;
use Illuminate\Database\Eloquent\Model;

class businessgroups extends Model
{
    use HasFactory, BelongsToBusiness;
}
