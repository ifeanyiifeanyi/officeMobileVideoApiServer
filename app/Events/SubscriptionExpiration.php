<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\ActivePlans;

class SubscriptionExpiration
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    

    /**
     * Create a new event instance.
     *
     * @return void
     */

     public $activePlans;
    public function __construct(ActivePlans $activePlans)
    {
        $this->activePlans = $activePlans;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
