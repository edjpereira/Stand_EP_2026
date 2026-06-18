<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'date'       => 'required|date',
            'type'       => 'required|string',
            'comment'    => 'nullable|string',
            'vehicle_id' => 'nullable|exists:vehicles,id',
        ]);


        Interaction::create($validated);


        return back()->with('success', 'Interação registada com sucesso!');
    }
}
