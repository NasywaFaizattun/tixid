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
        $hour = $schedule['hour'][$hourId] ?? ''; // kalau tidak ketemu jam nya, buat default kosong
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
        //
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
