use App\Http\Requests\InteractionRequest;
use App\Models\Interaction;
use Illuminate\Support\Facades\Storage;

public function store(InteractionRequest $request)
{
    $validated = $request->validated();

    // Tratamento do Anexo (Ponto 23)
    if ($request->hasFile('attachment')) {
        // Guarda na pasta privada 'interactions/attachments' dentro do storage/app
        $path = $request->file('attachment')->store('interactions/attachments');
        $validated['attachment_path'] = $path;
        $validated['attachment_name'] = $request->file('attachment')->getClientOriginalName();
    }

    // Grava a interação no histórico (Ponto 6)
    Interaction::create($validated);

    return redirect()->back()->with('success', 'Interação de CRM registada com sucesso!');
}
