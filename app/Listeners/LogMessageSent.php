<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogMessageSent
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        // Logic to execute when the message is sent
        // For example, log the message
        \Log::info('Message sent: ', $event->message->toArray());
    }
}
