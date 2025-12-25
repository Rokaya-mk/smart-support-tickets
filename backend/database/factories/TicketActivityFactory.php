<?php

namespace Database\Factories;

use App\Models\TicketActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TicketActivity>
 */
class TicketActivityFactory extends Factory
{
    protected $model = TicketActivity::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            
            'ticket_id' => \App\Models\Ticket::factory(),
            'activity_type' => $this->faker->randomElement(['created', 'status_changed', 'assigned', 'message_sent']),
            'description' => null,

        ];
    }
}
