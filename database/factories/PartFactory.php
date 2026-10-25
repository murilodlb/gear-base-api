<?php

namespace Database\Factories;

use App\Models\Part;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para geração de Peças Automotivas em testes e seeders
 *
 * @extends Factory<Part>
 */
class PartFactory extends Factory
{
    protected $model = Part::class;

    /**
     * Define o estado padrão do modelo com dados fictícios realistas.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $partNames = [
            'Pastilha de Freio Dianteira',
            'Filtro de Óleo do Motor',
            'Filtro de Ar Condicionado',
            'Correia Dentada',
            'Vela de Ignição',
            'Amortecedor Dianteiro',
            'Disco de Freio Ventilado',
            'Bomba de Combustível',
            'Sensor de Oxigênio (Sonda Lambda)',
            'Óleo Sintético 5W30 (1L)',
            'Fluido de Freio DOT 4',
            'Palheta do Limpador de Parabrisa',
        ];

        $cost = fake()->randomFloat(2, 20, 300);
        $sale = round($cost * fake()->randomFloat(2, 1.3, 1.8), 2); // Margem de lucro de 30% a 80%

        return [
            'name'           => fake()->randomElement($partNames) . ' ' . fake()->randomNumber(3, true),
            'sku'            => 'SKU-' . strtoupper(fake()->bothify('???-####')),
            'stock_quantity' => fake()->numberBetween(5, 50),
            'cost_price'     => $cost,
            'sale_price'     => $sale,
        ];
    }
}
