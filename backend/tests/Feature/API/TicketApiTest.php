<?php

namespace Tests\Feature\API;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TicketApiTest extends TestCase
{
    use RefreshDatabase;

    // test ticket list of user
    public function test_user_can_view_own_tickets()
    {
        $user = User::factory()->create(['role' => 'user']);
        Ticket::factory()->create(['user_id' => $user->id]);
        Ticket::factory()->create(); 

        $this->actingAs($user)->getJson('/api/tickets')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    } 

    // test ticket detail view
    public function test_user_can_view_own_ticket_detail(){
         $user = User::factory()->create(['role' => 'user']);
        $ticket= Ticket::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->getJson("/api/tickets/{$ticket->id}")
            ->assertStatus(200)
            ->assertJsonFragment([
                'id' => $ticket->id,
                'user_id' => $user->id,
            ]);
    }
}
