<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Models\TicketActivity;

class TicketObserver
{
    /**
     * Handle the Ticket "created" event.
     */
    public function created(Ticket $ticket): void
    {
         TicketActivity::create([
            'ticket_id' => $ticket->id,
            'activity_type' => 'created',
            'description' => [
                'by' => auth()->id(),
            ]
        ]);
    }

    /**
     * Handle the Ticket "updated" event.
     */
    public function updated(Ticket $ticket): void
    {
         if ($ticket->wasChanged('status')) {
            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'activity_type' => 'status_changed',
                'description' => [
                    'by' => auth()->id(),
                    'old_status' => $ticket->getOriginal('status'),
                    'new_status' => $ticket->status,
                ]
            ]);
         }
         if ($ticket->wasChanged('assigned_to')) {
            TicketActivity::create([
                'ticket_id' => $ticket->id,
                'activity_type' => 'assigned',
                'description' => [
                    'by' => auth()->id(),
                    'old_assigned_to' => $ticket->getOriginal('assigned_to'),
                    'new_assigned_to' => $ticket->assigned_to,
                ]
            ]);
         }
    }

    /**
     * Handle the Ticket "deleted" event.
     */
    public function deleted(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "restored" event.
     */
    public function restored(Ticket $ticket): void
    {
        //
    }

    /**
     * Handle the Ticket "force deleted" event.
     */
    public function forceDeleted(Ticket $ticket): void
    {
        //
    }
}
