<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketReplyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketActivityTest extends TestCase
{
     use RefreshDatabase;
    /**
     * test if activity is logged when ticket is created
     */
    public function test_ticket_creation_logs_activity(): void
    {
        // create admin 
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // create a ticket 
        $ticket = Ticket::factory()->create(['user_id' => $admin->id]);

        // assert activity is logged
        $this->assertDatabaseHas('ticket_activities', [
            'ticket_id' => $ticket->id,
            'activity_type' => 'created',
        ]);

    }

    /**
     * test if activity is logged when ticket status is changed
     */
    public function test_activity_is_logged_when_status_changed(){
          // create admin 
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // create a ticket 
        $ticket = Ticket::factory()->create(['user_id' => $admin->id]);

        $ticket->update(['status' => 'pending']);
        
        $this->assertDatabaseHas('ticket_activities', [
            'ticket_id' => $ticket->id,
            'activity_type' => 'status_changed',
        ]);

    }

   
}
