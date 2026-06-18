<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Client extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'taxId',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
    public function getVehiclesAttribute()
    {
        // Recolhe os veículos de todas as vendas deste cliente
        return $this->sales->map(function ($sale) {
            return $sale->vehicle;
        })->filter(); // filter() remove eventuais nulos
    }
    public function interactions()
    {
        // Um cliente tem muitas interações
        return $this->hasMany(Interaction::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }
}

