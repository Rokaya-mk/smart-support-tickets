<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Services\TicketReplyService;
use Illuminate\Http\Request;

class TicketMessageController extends Controller
{
    protected TicketReplyService $ticketReplyService;
    public function __construct(TicketReplyService $ticketReplyService)
    {
        $this->ticketReplyService = $ticketReplyService;
    }
    // save message 
    public function store(Request $request,Ticket $ticket){
        $request->validate([
            'message' => 'required|string|min:3'
        ]);

        $this->ticketReplyService->reply($ticket,$request->message);


        return response()->json([
            'message' => 'Reply sent successfully'
        ]);

        
    }
}
