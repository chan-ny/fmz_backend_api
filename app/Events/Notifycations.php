<?php

namespace App\Events;

use App\Models\Customer\Invoince;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class Notifycations implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $confirmBill = "";
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($confirmBill)
    {
        $this->confirmBill = $confirmBill;
    }

    public function broadcastWith()
    {
        $invoince = DB::table('invoinces')->orderByDesc('invId')->first();
        $value = DB::table('payments')->get();

        if (!is_null($this->confirmBill)) {
            return [
                "value" => $this->confirmBill,
            ];
        } else {
            return [
                "data_inv" => $invoince,
            ];
        }
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('channel');
    }
}
