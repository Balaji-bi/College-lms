<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'message', 
        'type', 
        'file_path', 
        'reply_to_id'
    ];

    /**
     * Get the user who sent this message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the likes for this message
     */
    public function likes()
    {
        return $this->hasMany(ForumMessageLike::class, 'message_id');
    }

    /**
     * Get the message this is a reply to
     */
    public function replyTo()
    {
        return $this->belongsTo(ForumMessage::class, 'reply_to_id');
    }

    /**
     * Get all replies to this message
     */
    public function replies()
    {
        return $this->hasMany(ForumMessage::class, 'reply_to_id');
    }
    
    /**
     * Check if the message is liked by a specific user
     */
    public function isLikedByUser($userId)
    {
        return $this->likes()->where('user_id', $userId)->exists();
    }
    
    /**
     * Get the total number of likes
     */
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }
}
