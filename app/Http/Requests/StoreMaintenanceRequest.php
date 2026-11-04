<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para Abertura de Ordem de Manutenção (Maintenance)
 *
 * Além dos dados básicos do veículo e mecânico responsável, suporta o envio
 * de uma lista de peças utilizadas logo na abertura da ordem de serviço.
 */
class StoreMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para a manutenção.
     *
     * O Laravel possui sintaxe especializada com asterisco ('*') para validar
     * arrays multidimensionais e coleções de objetos aninhados no JSON.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'exists:users,id': Garante integridade referencial antes mesmo de tocar no banco.
            // Se o user_id não existir na tabela users, rejeita imediatamente com 422.
            'user_id'          => 'required|integer|exists:users,id',

            'description'      => 'required|string',
            'vehicle_plate'    => 'required|string|max:20',
            'vehicle_model'    => 'required|string|max:100',

            // Status inicial da ordem (ex: 'pending', 'in_progress', 'completed', 'cancelled')
            'status'           => 'sometimes|string|in:pending,in_progress,completed,cancelled',

            // Custo da mão de obra (Mão de obra do mecânico, sem contar peças)
            'labor_cost'       => 'nullable|numeric|min:0',

            // Datas de entrada e entrega
            'entry_date'       => 'required|date',
            // 'after_or_equal:entry_date': Impede a anomalia temporal de entrega anterior à entrada!
            'delivery_date'    => 'nullable|date|after_or_equal:entry_date',

            // Validação de Peças Associadas (Relacionamento Many-to-Many com Pivot):
            // O payload pode conter: "parts": [ {"id": 1, "quantity": 2}, {"id": 4, "quantity": 1} ]
            'parts'            => 'nullable|array',
            'parts.*.id'       => 'required_with:parts|integer|exists:parts,id',
            'parts.*.quantity' => 'required_with:parts|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'             => 'O mecânico responsável é obrigatório.',
            'user_id.exists'               => 'O mecânico selecionado não foi encontrado no sistema.',
            'description.required'         => 'A descrição dos serviços da manutenção é obrigatória.',
            'vehicle_plate.required'       => 'A placa do veículo é obrigatória.',
            'vehicle_model.required'       => 'O modelo do veículo é obrigatório.',
            'status.in'                    => 'Status inválido. Use: pending, in_progress, completed ou cancelled.',
            'entry_date.required'          => 'A data de entrada do veículo é obrigatória.',
            'entry_date.date'              => 'A data de entrada deve ser uma data válida.',
            'delivery_date.after_or_equal' => 'A data de entrega não pode ser anterior à data de entrada.',
            'parts.array'                  => 'O campo de peças deve ser uma lista (array).',
            'parts.*.id.exists'            => 'Uma ou mais peças selecionadas não existem no catálogo.',
            'parts.*.quantity.min'         => 'A quantidade de cada peça utilizada deve ser no mínimo 1.',
        ];
    }
}
