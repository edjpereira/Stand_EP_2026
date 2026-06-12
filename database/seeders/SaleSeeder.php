<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Vehicle;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Buscar todos os clientes e viaturas que já foram criados pelos seeders anteriores
        $clients = Client::all();
        $vehicles = Vehicle::all();

        // Segurança: Se por algum motivo não houver dados, não fazemos nada
        if ($clients->isEmpty() || $vehicles->isEmpty()) {
            return;
        }

        // 2. Vamos criar 5 vendas realistas usando os primeiros 5 carros do stock
        for ($i = 0; $i < 5; $i++) {
            // Garante que não rebenta se tiveres menos de 5 carros no total
            if (isset($vehicles[$i])) {
                $vehicle = $vehicles[$i];

                Sale::create([
                    'client_id'   => $clients->random()->id, // Escolhe um cliente ao calhas
                    'vehicle_id'  => $vehicle->id,
                    'sale_date'   => Carbon::now()->subDays(rand(1, 30))->format('Y-m-d'), // Venda algures no último mês
                    'sale_amount' => $vehicle->price, // O valor da venda bate certo com o preço do carro
                    'notes'       => 'Venda gerada automaticamente pelo sistema de testes.',
                ]);

                // Regra de Negócio: Atualizar o estado do carro para vendido
                $vehicle->update(['status' => 'sold']);
            }
        }
    }
}
