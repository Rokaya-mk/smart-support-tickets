<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    // index
    public function index(Request $request){
        $user = $request->user();

        $tickets = Ticket::query()
                    ->when($user->role == 'user' ,fn($query) => $query->where('user_id',$user->id))
                    ->with(['agent'])
                    ->latest()
                    ->paginate(10);

        return response()->json($tickets);
    }


    // show 
    public function show (Ticket $ticket){
        Gate::authorize('view',$ticket);

        return response()->json([
            'ticket' => $ticket->load(['messages.user','activities'])
        ]);
        
    }
}
