<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id', // Add this field
        'user_type', // For multiple user types
        'year', 'phone', 'role', 'profile_photo', 'college'

    ];
    // app/Models/User.php

    

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    // app/Models/User.php (Add these methods to your existing User model)

    /**
     * Get all forum messages sent by the user
     */
    public function forumMessages()
    {
        return $this->hasMany(ForumMessage::class);
    }

    /**
     * Get all message likes by the user
     */
    public function messageLikes()
    {
        return $this->hasMany(ForumMessageLike::class);
    }

}