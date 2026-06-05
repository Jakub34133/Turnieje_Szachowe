<?php

namespace App\Http\Controllers;

use App\Models\Turniej;
use Illuminate\Http\Request;

class TurniejController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $turnieje = Turniej::all();
        return view('turnieje.index', [
            'turnieje' => $turnieje
        ]);
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
    public function show(Turniej $turniej)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Turniej $turniej)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Turniej $turniej)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Turniej $turniej)
    {
        //
    }
}
