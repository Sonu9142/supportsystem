<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        Log::info('Incoming Ticket Request:', $request->all());

        $validator = Validator::make($request->all(), [
            'issueCategory' => 'required|in:transactional,non-transactional',
            'services' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'transactionId' => 'nullable|string|required_if:issueCategory,transactional',
            'fileUpload' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $filePath = null;
        if ($request->hasFile('fileUpload')) {
            try {
                $filePath = $request->file('fileUpload')->store('screenshots', 'public');
            } catch (\Exception $e) {
                Log::error('File upload failed: ' . $e->getMessage());
                return response()->json(['message' => 'File upload failed.'], 500);
            }
        }

        $ticketId = 'TKT-' . strtoupper(Str::random(6));

        $developer = User::where('role', 'developer')
            ->where('is_active', true)
            ->withCount(['tickets' => function ($q) {
                $q->where('acknowledged', false);
            }])
            ->orderBy('tickets_count', 'asc')
            ->first();

        $assignedTo = $developer ? $developer->id : null;

        $ticket = Ticket::create([
            'ticket_id' => $ticketId,
            'issue_category' => $request->issueCategory,
            'services' => $request->services,
            'title' => strip_tags($request->title),
            'description' => strip_tags($request->description),
            'transaction_id' => $request->transactionId,
            'file_path' => $filePath,
            'assigned_to' => $assignedTo,
            'assigned_at' => now(),
        ]);

        return response()->json([
            'message' => 'Ticket created successfully!',
            'data' => $ticket,
            'assigned_to' => $developer ? $developer->name : 'No developer available'
        ], 201);
    }

    public function getAllTickets()
    {
        $tickets = Ticket::with('developer')->latest()->get();
        return response()->json(['tickets' => $tickets]);
    }

    public function updateTicket(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'status' => 'sometimes|in:pending,acknowledged,resolved,reassigned',
            'fileUpload' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('fileUpload')) {
            try {
                $ticket->file_path = $request->file('fileUpload')->store('screenshots', 'public');
            } catch (\Exception $e) {
                Log::error('File upload failed: ' . $e->getMessage());
                return response()->json(['message' => 'File upload failed.'], 500);
            }
        }

        if ($request->filled('title')) $ticket->title = strip_tags($request->title);
        if ($request->filled('description')) $ticket->description = strip_tags($request->description);
        if ($request->filled('status')) $ticket->status = $request->status;

        $ticket->save();

        return response()->json([
            'message' => 'Ticket updated successfully!',
            'data' => $ticket
        ]);
    }

    public function acknowledge($id)
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->acknowledged) {
            return response()->json(['message' => 'Already acknowledged.'], 400);
        }

        $ticket->update([
            'acknowledged' => true,
            'acknowledged_at' => Carbon::now(),
            'status' => 'acknowledged'
        ]);

        return response()->json(['message' => 'Ticket acknowledged successfully!']);
    }
}
