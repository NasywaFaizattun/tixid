<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Schedule;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    public function showSeats($scheduleId, $hourId) {
        $schedule = Schedule::find($scheduleId);
        //  ambil jam index nya sesuai params route
        $hour = $schedule['hours'][$hourId] ?? ''; // kalau tidak ketemu jam nya, buat default kosong
        return view('schedule.row-seats', compact('schedule', 'hour'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'schedule_id' => 'required',
            'date' => 'required',
            'hour' => 'required',
            'rows_of_seats' => 'required',
            'quantity' => 'required',
            'total_price' => 'required',
            'service_fee' => 'required',
        ]);

        $createData = Ticket::create([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'hour' => $request->hour,
            'rows_of_seats' => $request->rows_of_seats,
            'quantity' => $request->quantity,
            'total_price' => $request->total_price,
            'service_fee' => $request->service_fee,
            'actived' => 0, // blm aktif sebelum dibayar
        ]);

        // karena ini diproses di JS jd return nya juga bentuk response JS (json)
        return response()->json([
            'message' => 'Berhasil membuat data tiket!',
            'data' => $createData
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}
