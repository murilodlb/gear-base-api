<?php

namespace Database\Seeders;

use App\Models\Maintenance;
use App\Models\Part;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DatabaseSeeder - Inicializador de Dados para Desenvolvimento
 *
 * Seeders são usados para preencher o banco de dados com dados iniciais ou fictícios
 * após a execução das migrações. Isso permite testar a API imediatamente via Postman/Insomnia
 * sem precisar cadastrar dados manualmente um a um.
 *
 * Para rodar este seeder:
 * php artisan db:seed
 * Ou recriando o banco do zero:
 * php artisan migrate:fresh --seed
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Popula a base de dados com usuários, peças, ferramentas e ordens de serviço.
     */
    public function run(): void
    {
        // 1. Criação dos Usuários / Mecânicos Padrão com senhas conhecidas para login imediato
        $admin = User::firstOrCreate(
            ['email' => 'admin@gearbase.com'],
            [
                'name'     => 'Administrador Geral',
                'password' => Hash::make('password123'),
            ]
        );

        $mechanic = User::firstOrCreate(
            ['email' => 'mecanico@gearbase.com'],
            [
                'name'     => 'Carlos Mecânico Chefe',
                'password' => Hash::make('password123'),
            ]
        );

        // Gera mais 3 mecânicos adicionais usando a UserFactory
        $extraMechanics = User::factory(3)->create();
        $allUsers = collect([$admin, $mechanic])->merge($extraMechanics);

        // 2. Criação do Catálogo de Peças (20 itens com estoque inicial variado)
        $parts = Part::factory(20)->create();

        // 3. Criação do Inventário de Ferramentas (10 ferramentas)
        Tool::factory(10)->create();

        // 4. Criação de Ordens de Serviço (Manutenções) com associação de mecânico e peças
        Maintenance::factory(8)->make()->each(function ($maintenance) use ($allUsers, $parts) {
            // Atribui aleatoriamente a um dos mecânicos cadastrados
            $maintenance->user_id = $allUsers->random()->id;
            $maintenance->save();

            // Seleciona de 1 a 3 peças aleatórias para vincular a esta manutenção
            $selectedParts = $parts->random(rand(1, 3));
            $syncData = [];

            foreach ($selectedParts as $part) {
                $qty = rand(1, 3);
                $syncData[$part->id] = ['quantity' => $qty];

                // Decrementa estoque para manter coerência
                if ($part->stock_quantity >= $qty) {
                    $part->decrement('stock_quantity', $qty);
                }
            }

            // Anexa as peças na tabela intermediária 'maintenance_part'
            $maintenance->parts()->attach($syncData);
        });
    }
}
