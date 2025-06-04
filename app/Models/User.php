<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'category',
        'business_id',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function business()
    {
        return $this->hasOne(businesses::class);
    }

    public function businesses()
    {
        return $this->belongsTo(businesses::class, 'business_id', 'id');
    }

    public function personnel()
    {
        return $this->hasOne(Personnel::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function projects()
    {
        return $this->hasMany(projects::class, 'client_id', 'id');
    }

    public function tasks()
    {
        return $this->hasMany(tasks::class, 'assigned_to', 'id');
    }
}
