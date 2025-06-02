<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Personnel extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'othername',
        'email',
        'designation',
        'business_id',
        'user_id',
        'marital_status',
        'employment_date',
        'category',
        'department',
        'phone_number',
        'picture',
        'cv',
        'state_of_origin',
        'address',
        'salary',
        'highest_certificate',
        'staff_id',
        'dob',
        'nationality',
    ];

    protected $casts = [
        'dob' => 'datetime',
        'employment_date' => 'datetime',
        'salary' => 'decimal:2',
    ];


    protected static function booted()
    {
        static::creating(function ($personnel) {
            $personnel->staff_id = self::generateUniqueStaffId();
        });
    }

    public static function generateUniqueStaffId()
    {
        // Generate abbrevation from Business company name
        $stopWords = ['and', 'of', 'the', 'in', 'on', 'at', 'for', 'to', 'with', 'a', 'an', 'by'];
        // Split the string into words
        $words = explode(" ", Auth::user()->business->business_name);
        $abbreviation = "";

        foreach ($words as $word) {
            $word = strtolower(trim($word)); // normalize and trim whitespace
            if (!empty($word) && !in_array($word, $stopWords)) {
                $abbreviation .= strtoupper($word[0]);
            }
        }

        do {
            $staffId = $abbreviation . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT) . strtoupper(Str::random(3));
        } while (self::where('staff_id', $staffId)->exists());

        return $staffId;
    }

    // ORM ELOQUENT RELATIONSHIP
    public function business()
    {
        return $this->belongsTo(businesses::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
