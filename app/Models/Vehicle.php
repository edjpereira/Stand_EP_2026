<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Illuminate\Database\Eloquent\SoftDeletes;

    class Vehicle extends Model
    {
        use HasFactory;
        use SoftDeletes;

        protected $fillable = [
            'make',
            'model',
            'plate',
            'year',
            'mileage',
            'price',
            'photo',
            'status',
        ];

        public function sale(): HasOne
        {
            return $this->hasOne(Sale::class);
        }
    }
