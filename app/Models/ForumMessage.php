<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForumMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'message',
        'image_path',
        'reply_to_id',
        'liked_by',
    ];

    protected $casts = [
        'liked_by' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(ForumMessage::class, 'reply_to_id');
    }

    public function replies()
    {
        return $this->hasMany(ForumMessage::class, 'reply_to_id');
    }
}