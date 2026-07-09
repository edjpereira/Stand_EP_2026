<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Client;
use App\Models\Interaction;
use App\Models\Vehicle;

class CrmSection extends Component
{
    use WithFileUploads;

    public Client $client;

    public $interaction_date;
    public $type = 'phone';
    public $vehicle_id;
    public $comment;
    public $attachment;

    protected $rules = [
        'interaction_date' => 'required|date',
        'type' => 'required|in:phone,email,visit,site',
        'vehicle_id' => 'nullable|exists:vehicles,id',
        'comment' => 'required|string|min:5',
        'attachment' => 'nullable|file|max:10240',
    ];

    public function mount(Client $client)
    {
        $this->client = $client;
        $this->interaction_date = now()->format('Y-m-d\TH:i');
    }

    public function store()
    {
        $this->validate();

        $path = null;
        if ($this->attachment) {
            $path = $this->attachment->store('attachments', 'public');
        }

        $this->client->interactions()->create([
            'user_id' => auth()->id(),
            'date' => $this->interaction_date,
            'type' => $this->type,
            'vehicle_id' => $this->vehicle_id,
            'comment' => $this->comment,
            'attachment' => $path,
        ]);

        $this->reset(['comment', 'attachment', 'vehicle_id']);
        $this->interaction_date = now()->format('Y-m-d\TH:i');

        $this->dispatch('crm-success', message: 'Ação comercial registada com sucesso!');
    }

    public function destroyInteraction($id)
    {
        $interaction = Interaction::findOrFail($id);

        if (auth()->user()->can('admin-only')) {
            $interaction->delete();
            $this->dispatch('crm-success', message: 'Interação removida do histórico com sucesso!');
        }
    }

    public function setNow()
    {
        $this->interaction_date = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        return view('livewire.crm-section', [
            'interactions' => $this->client->interactions()->orderBy('date', 'desc')->get(),
            'vehicles' => Vehicle::all()
        ]);
    }
}
