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
        'first_name',
        'last_name',
        'other_name',
        'email',
        'designation',
        'business_id',
        'user_id',
        'marital_status',
        'employment_date',
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

    /**
     * Get the Agent's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    protected static function booted()
    {
        static::creating(function ($personnel) {
            $personnel->staff_id = self::generateUniqueStaffId();
        });
    }

    public static function generateUniqueStaffId()
    {
        // Generate abbreviation from Business company name
        $stopWords = ['and', 'of', 'the', 'in', 'on', 'at', 'for', 'to', 'with', 'a', 'an', 'by'];
        $user = Auth::user();
        $business = $user && $user->business ? $user->business : null;
        $businessName = $business ? $business->business_name : 'RP'; // Default to 'RP' if no business found
        // Split the string into words
        $words = explode(" ", $businessName);
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
        return $this->belongsTo(Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
