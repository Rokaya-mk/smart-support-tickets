<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;
    protected function afterCreate(): void
{
    $ticket = $this->record;

    if ($message = $this->data['initial_message'] ?? null) {
        $ticket->messages()->create([
            'user_id' => $ticket->user_id,
            'subject' => $message,
        ]);
    }
}

}
