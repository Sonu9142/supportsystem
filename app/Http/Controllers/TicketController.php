<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validator = Validator::make($request->all(), [
            'ticketId'        => 'required|string|unique:tickets',
            'issueCategory'   => 'required|string|in:Transaction,Non-Transactional',
            'services'        => 'required|string',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'transactionId'   => 'required_if:issueCategory,Transaction|string|nullable',
            'fileUpload'      => 'required_if:issueCategory,Non-Transactional|image|mimes:jpeg,png,jpg|max:2048|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        $filePath = null;

        // 2. Handle File Upload
        if ($request->hasFile('fileUpload')) {
            try {
                // Store uploaded file in storage/app/public/screenshots
                $path = $request->file('fileUpload')->store('screenshots', 'public');
                $filePath = $path;
            } catch (\Exception $e) {
                Log::error('File upload failed: ' . $e->getMessage());
                return response()->json([
                    'message' => 'File upload failed, please try again.',
                ], 500);
            }
        }

        // 3. Sanitize Input Data
        $sanitizedData = [
            'ticketId'      => $request->ticketId,
            'issueCategory' => $request->issueCategory,
            'services'      => $request->services,
            'title'         => strip_tags($request->title),
            'description'   => strip_tags($request->description),
            'transactionId' => $request->transactionId,
            'filePath'      => $filePath,
        ];

        // 4. Create Ticket in Database
        try {
            $ticket = Ticket::create($sanitizedData);
            return response()->json([
                'message' => 'Ticket created successfully!',
                'data'    => $ticket
            ], 201);
        } catch (\Exception $e) {
            Log::error('Database save failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'An error occurred while saving the ticket.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
