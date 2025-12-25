<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\User;
use App\Services\TicketReplyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketMessageTest extends TestCase
{
    use RefreshDatabase;
    // check i agent can answer on message ticket 
    public function tet_agent_can_answer_on_message_ticket(){
        $agent = User::factory()->create(['role' => 'agent'] );

        $this->actingAs($agent);
        $ticket = Ticket::factory()->create();
        app(TicketReplyService::class)
            ->reply($ticket, 'Agent reply');

        $this->assertDatabaseHas('ticket_messages', [
            'ticket_id' => $ticket->id,
            'user_id' => $agent->id,
            'message' => 'Agent reply',
        ]);
        
        
    }

    // check if admin can answer on message ticket
    public function tet_admin_can_answer_on_message_ticket(){
        $admin = User::factory()->create(['role' => 'agent'] );

        $this->actingAs($admin);
        $ticket = Ticket::factory()->create();
        app(TicketReplyService::class)
            ->reply($ticket, 'Admin reply');

        $this->assertDatabaseHas('ticket_messages', [
            'ticket_id' => $ticket->id,
            'user_id' => $admin->id,
            'message' => 'Admin reply',
        ]);
          
    }

     /**
     * test if user has not permission to create message ticket
     *
     */
    public function test_user_cannot_create_message_ticket()
    {
        $user = User::factory()->create(['role' => 'user']);
        $ticket = Ticket::factory()->create();

        $this->actingAs($user);

        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);

        app(TicketReplyService::class)
            ->reply($ticket, 'Unauthorized message');
    }
}
