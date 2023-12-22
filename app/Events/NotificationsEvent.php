<?php

namespace App\Events;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class NotificationsEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public function __construct($user_id)
    {
        $this->user = $user_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    // You should specify the name of the channel created in Pusher.
    public function broadcastOn()
    {
        return 'user.' . $this->user;
    }

    /**
     * Get the name of the broadcast event.
     *
     * @return string
     */
    // You should specify the name of the generated notification.
    public function broadcastAs() {
        return 'notification';
    }

}
