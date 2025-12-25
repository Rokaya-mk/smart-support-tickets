<?php

namespace App\Services;

use App\Models\Ticket;
use Illuminate\Support\Facades\Gate;

class TicketReplyService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function reply(Ticket $ticket, string $message): void
    {
        Gate::authorize('reply', $ticket);

        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'message' => $message,
        ]);
    }
}
