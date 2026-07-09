namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InteractionRequest extends FormRequest
{
public function authorize(): bool
{
return true;
}

public function rules(): array
{
return [
'client_id' => 'required|exists:clients,id',
'vehicle_id' => 'nullable|exists:vehicles,id',
'type' => 'required|in:phone,email,visit,site',
'notes' => 'required|string|min:10',
'interaction_date' => 'required|date|before_or_equal:now',
'attachment' => 'nullable|file|max:5120|mimes:pdf,jpeg,png,jpg,docx,eml', // Adicionado eml se for necessário
];
}

public function messages(): array
{
return [
'client_id.required' => 'É obrigatório selecionar um cliente.',
'type.required' => 'Selecione o tipo de interação.',
'notes.required' => 'Insira as notas/resumo da interação.',
'notes.min' => 'O resumo deve ter pelo menos 10 caracteres.',
'interaction_date.required' => 'A data da interação é obrigatória.',
'interaction_date.before_or_equal' => 'A data não pode ser futura.',
'attachment.max' => 'O anexo não pode ser superior a 5MB.',
'attachment.mimes' => 'Apenas são permitidos ficheiros PDF, Imagens (JPG/PNG) ou Word (DOCX).',
];
}
}
