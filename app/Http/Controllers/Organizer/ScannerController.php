<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Services\TicketService;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    public function index()
    {
        return view('organizer.scanner');
    }

    public function checkIn(Request $request, TicketService $ticketService)
    {
        $request->validate(['ticket_code' => 'required|string|max:16']);

        $result = $ticketService->checkIn($request->ticket_code);

        if ($request->wantsJson()) {
            return response()->json($result, $result['success'] ? 200 : 422);
        }

        return back()->with(
            $result['success'] ? 'scan_success' : 'scan_error',
            $result['message']
        )->with('scan_attendee', $result['attendee']);
    }
}
