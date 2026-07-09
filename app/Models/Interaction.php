<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interaction extends Model
{
    protected $fillable = ['client_id', 'user_id', 'date', 'type', 'comment', 'vehicle_id', 'attachment'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle(): BelongsTo
    {
        // Se a tua chave estrangeira na tabela de interações se chamar 'vehicle_id',
        // o Laravel assume-a automaticamente aqui.
        return $this->belongsTo(Vehicle::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
