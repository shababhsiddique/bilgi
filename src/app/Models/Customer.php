<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable, softDeletes;

    /**
     * The table associated with the model.
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password',
        'is_guest_registered',
        'otp_code',
        'otp_expires_at',
        'phone_verified_at',

    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'password' => 'hashed',
        'is_guest_registered' => 'boolean',
    ];

    public function getNameAttribute(): string
    {
        return trim($this->full_name);
    }


    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * Generate and set OTP for phone verification
     */
    public function generateOTP(): string
    {
        $otpLength = (int) config('auth.one_time_password_length', 6);
        $maxValue = (int) (pow(10, $otpLength) - 1);
        $otp = str_pad((string) random_int(0, $maxValue), $otpLength, '0', STR_PAD_LEFT);
        $otpExpiryMinutes = (int) config('auth.one_time_password_expiry', 5);

        $this->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes($otpExpiryMinutes),
        ]);
        return $otp;
    }

    /**
     * Verify OTP code
     */
    public function verifyOTP(string $otp): bool
    {
        if ($this->otp_code === $otp && $this->otp_expires_at && $this->otp_expires_at->isFuture()) {
            $this->update([
                'phone_verified_at' => now(),
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);
            return true;
        }
        return false;
    }

}
