<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function index()
    {
        return response()->json(Ticket::with(['user', 'category', 'assignee'])->get());
    }

    public function show($id)
    {
        return response()->json(Ticket::with(['user', 'category', 'assignee'])->findOrFail($id));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'in:open,in_progress,on_hold,resolved,closed',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        $ticket = Ticket::create($validated);

        return response()->json($ticket, 201);
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'in:open,in_progress,on_hold,resolved,closed',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        $ticket->update($validated);

        return response()->json($ticket);
    }

    public function details($id)
    {
        $ticket = Ticket::with(['user', 'category', 'assignee'])->findOrFail($id);

        $details = [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'priority' => $ticket->priority,
            'status' => $ticket->status,
            'created_at' => $ticket->created_at,
            'updated_at' => $ticket->updated_at,
            'user' => $ticket->user,
            'category' => $ticket->category,
            'assignee' => $ticket->assignee,
        ];

        return response()->json($details);
    }


    public function destroy($id)
    {
        Ticket::findOrFail($id)->delete();
        return response()->json(['message' => 'Ticket deleted']);
    }
}
