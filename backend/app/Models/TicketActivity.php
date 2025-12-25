<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Testing\Fluent\Concerns\Has;

class TicketActivity extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket_id',
        'activity_type',
        'description',
    ];

    protected $casts = [
        'description' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

     public function getDescriptionAttribute(): string
    {
        return match ($this->activity_type) {
            'created' => 'Ticket created',

            'status_changed' =>
                'Status changed from ' .
                Str::title($this->description['old_status'] ?? '') .
                ' to ' .
                Str::title($this->description['new_status'] ?? ''),

            'assigned' =>
                'Ticket assigned to agent #' . ($this->description['new_assigned_to'] ?? '—'),

            'message_sent' =>
                'Message sent',

            default =>
                Str::title(str_replace('_', ' ', $this->activity_type)),
        };
    }
}
