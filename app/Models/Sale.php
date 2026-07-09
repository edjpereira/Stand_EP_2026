<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'vehicle_id',
        'user_id', // <-- Adicionado para registar o vendedor
        'sale_date',
        'sale_amount',
        'notes',
    ];

    /**
     * Definir as conversões automáticas de tipos de dados.
     */
    protected $casts = [
        'sale_date' => 'date', // <-- Transforma a string da BD num objeto Carbon automaticamente
        'sale_amount' => 'decimal:2',
    ];

    /**
     * Relação: Uma venda pertence a um cliente.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relação: Uma venda pertence a uma viatura.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Relação: Uma venda foi efetuada por um utilizador do sistema (Employee/Admin).
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
