<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumMessageLike extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message_id'];

    /**
     * Get the user who liked the message
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the message that was liked
     */
    public function message()
    {
        return $this->belongsTo(ForumMessage::class, 'message_id');
    }
}
