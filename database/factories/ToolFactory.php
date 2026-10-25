<?php

namespace Database\Factories;

use App\Models\Tool;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory para geração de Ferramentas de Oficina
 *
 * @extends Factory<Tool>
 */
class ToolFactory extends Factory
{
    protected $model = Tool::class;

    /**
     * Define o estado padrão com ferramentas comuns de oficina mecânica.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $toolNames = [
            'Torquímetro de Estalo 1/2 Pol.',
            'Chave de Impacto Pneumática',
            'Scanner Automotivo OBD2',
            'Multímetro Digital Automotivo',
            'Elevador Automotivo Hidráulico',
            'Jogo de Chaves Combinadas 6 a 32mm',
            'Compressor de Molas de Suspensão',
            'Sangrador de Freio por Pressão',
            'Manômetro de Pressão de Combustível',
            'Chave Phillips com Cabo Isolado',
        ];

        return [
            'name'          => fake()->randomElement($toolNames) . ' ' . fake()->randomNumber(2, true),
            'serial_number' => 'SN-' . strtoupper(fake()->bothify('##??-####')),
            'status'        => fake()->randomElement(['available', 'available', 'in_use', 'maintenance']),
        ];
    }
}
