<?php

namespace App\Events;

use App\Models\ForumMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ForumMessageLiked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;
    public $action;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ForumMessage $message, $userId, $action)
    {
        $this->message = $message;
        $this->userId = $userId;
        $this->action = $action; // 'liked' or 'unliked'
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('forum');
    }
}
