<?php

namespace App\Http\Controllers;

use App\Models\Interaction;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function store(Request $request)
{
    $validated = $request->validate([
        'client_id' => 'required|exists:clients,id',
        'interaction_date' => 'required|string',
        'type' => 'required|string',
        'comment' => 'required|string',
        'vehicle_id' => 'nullable|exists:vehicles,id',
        'attachment' => 'nullable|file|max:5120',
    ]);

    // 1. CORREÇÃO DA HORA: Converte o formato do datetime-local mantendo horas e minutos intactos
    $validated['date'] = date('Y-m-d H:i:s', strtotime($request->input('interaction_date')));

    // 2. CORREÇÃO DO UTILIZADOR: Guarda o ID do utilizador logado na coluna user_id
    // Sem mexer ou injetar nada no texto do comentário!
    $validated['user_id'] = auth()->id();

    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $originalName = $file->getClientOriginalName();
        $path = $file->storeAs('attachments', time() . '_' . $originalName, 'public');
        $validated['attachment'] = $path;
    }

    Interaction::create($validated);

    return back()->with('success', 'Interação registada com sucesso!');
}

    public function destroyInteraction($id)
    {
        // Proteção extra no backend caso tentem contornar o botão da UI
        if (!auth()->user()->can('admin-only')) {
            abort(403, 'Ação não autorizada.');
        }

        $interaction = Interaction::findOrFail($id);
        $interaction->delete();

        return back()->with('success', 'Interação eliminada do histórico.');
    }
}
