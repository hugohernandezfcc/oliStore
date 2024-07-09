<?php

namespace App\Http\Controllers;

use App\Models\WeekDay;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;


class WeekDayController extends Controller
{
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show(WeekDay $weekDay)
    {
        return Inertia::render('Weekdays/Show', [
            'customRecord' => WeekDay::with('createdBy', 'updatedBy')->find($weekDay->id)
        ] );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WeekDay $weekDay)
    {
        //
    }
 
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WeekDay $weekDay)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WeekDay $weekDay)
    {
        //
    }
}
