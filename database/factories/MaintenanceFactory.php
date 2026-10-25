<?php

namespace Database\Factories;

use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para geração de Ordens de Manutenção
 *
 * @extends Factory<Maintenance>
 */
class MaintenanceFactory extends Factory
{
    protected $model = Maintenance::class;

    /**
     * Define o estado padrão para ordens de serviço realistas.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $vehicleModels = [
            'Honda Civic 2.0 2021',
            'Toyota Corolla 2.0 2022',
            'Volkswagen Gol 1.6 2019',
            'Chevrolet Onix 1.0 Turbo 2023',
            'Fiat Strada 1.3 2022',
            'Hyundai HB20 1.0 2020',
            'Jeep Renegade 1.8 2021',
            'Ford Ka 1.5 2018',
        ];

        $descriptions = [
            'Revisão periódica de 40.000 km com troca de óleo e filtros.',
            'Barulho ao frear na roda dianteira esquerda. Substituição de pastilhas.',
            'Motor falhando em marcha lenta. Limpeza de bicos e troca de velas.',
            'Vazamento de fluido no sistema de arrefecimento. Teste de pressão.',
            'Alinhamento, balanceamento e troca dos amortecedores dianteiros.',
        ];

        $entryDate = fake()->dateTimeBetween('-30 days', 'now');
        $status = fake()->randomElement(['pending', 'in_progress', 'completed']);
        $deliveryDate = $status === 'completed'
            ? fake()->dateTimeBetween($entryDate, 'now')
            : null;

        return [
            // Relacionamento com mecânico: se user_id não for passado, cria um usuário automaticamente
            'user_id'       => User::factory(),
            'description'   => fake()->randomElement($descriptions),
            // Placa no padrão Mercosul (ex: ABC1D23)
            'vehicle_plate' => strtoupper(fake()->bothify('???#?##')),
            'vehicle_model' => fake()->randomElement($vehicleModels),
            'status'        => $status,
            'labor_cost'    => fake()->randomFloat(2, 100, 800),
            'entry_date'    => $entryDate,
            'delivery_date' => $deliveryDate,
        ];
    }
}
