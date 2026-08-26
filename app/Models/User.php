<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\User
 *
 * @mixin \Spatie\Permission\Traits\HasRoles
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // System Roles Constants
    public const ROLE_ADMIN = 'ADMIN';
    public const ROLE_DELIVERY = 'DELIVERY';
    public const ROLE_CUSTOMER = 'CUSTOMER';
    public const ROLE_KITCHEN = 'KITCHEN';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'delivery_zone_id',
        'vehicle_type',
        'vehicle_number',
        'address',
        'avatar',
        'rating_points', // Driver points සඳහා එකතු කරන ලදී
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'rating_points'     => 'integer', // Rating points integer ලෙස cast කර ඇත
        ];
    }

    /**
     * Helper methods to check user roles easily
     */
    public function isAdmin(): bool
    {
        return strtoupper($this->role) === self::ROLE_ADMIN;
    }

    public function isDelivery(): bool
    {
        return strtoupper($this->role) === self::ROLE_DELIVERY;
    }

    public function isKitchen(): bool
    {
        return in_array(strtoupper($this->role), [self::ROLE_KITCHEN, 'STAFF']);
    }

    public function isCustomer(): bool
    {
        return strtoupper($this->role) === self::ROLE_CUSTOMER;
    }

    /**
     * Get the delivery zone associated with the driver/staff member.
     */
    public function deliveryZone(): BelongsTo
    {
        return $this->belongsTo(DeliveryZone::class, 'delivery_zone_id');
    }

    /**
     * Get all deliveries assigned to this driver.
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class, 'driver_id');
    }

    /**
     * Get all customer orders placed by this user.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(CustomerOrder::class, 'user_id');
    }
}