<?php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemAddedToCart implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $addedItems;

    public function __construct($addedItems)
    {
        $this->addedItems = $addedItems;
    }

    public function broadcastOn()
    {
        return new Channel('cart');
    }

    // public function broadcastWith()
    // {
    //     return ['items' => $this->addedItems];
    // }
}


