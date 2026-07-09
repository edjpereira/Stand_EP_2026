<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    // IMPORTANTE: Adicionar todas as colunas para o Laravel permitir o AuditLog::create()
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'details',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
