<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone_country',
        'phone',
        'school_id',
        'photo',
        'motto',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'fcm_token'
    ];

    protected $appends = ['whatsapp_link'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function photo(): Attribute
    {
        return Attribute::make(
            get: fn ($photo) => config('app.url') . '/storage/' . $photo
        );
    }
    public $casts = [
        'phone_raw' => RawPhoneNumberCast::class,
        'phone_e164' => E164PhoneNumberCast::class,
    ];

    public function getFirstName() {
        return explode(' ', $this->name)[0];
    }

    public function getLastName() {
        return explode(' ', $this->name)[1];
    }

    protected function whatsappLink(): Attribute
    {
        return new Attribute(
            get: function () {
                $wa_phone = new PhoneNumber($this->phone, $this->phone_country);
                $wa_phone = $wa_phone->formatE164();
                return 'https://wa.me/' . $wa_phone;
            }
        );
    }

    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }
}
