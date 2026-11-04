<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para Atualização de Ordem de Manutenção (Maintenance)
 *
 * Suporta atualização de status (ex: de 'in_progress' para 'completed'),
 * alteração de mecânico responsável, custos ou modificação da lista de peças.
 */
class UpdateMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para atualização.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'          => 'sometimes|required|integer|exists:users,id',
            'description'      => 'sometimes|required|string',
            'vehicle_plate'    => 'sometimes|required|string|max:20',
            'vehicle_model'    => 'sometimes|required|string|max:100',
            'status'           => 'sometimes|required|string|in:pending,in_progress,completed,cancelled',
            'labor_cost'       => 'sometimes|nullable|numeric|min:0',
            'entry_date'       => 'sometimes|required|date',
            'delivery_date'    => 'sometimes|nullable|date',

            // Validação de Peças (Caso o cliente envie a chave 'parts', atualizamos o relacionamento e estoque)
            'parts'            => 'sometimes|nullable|array',
            'parts.*.id'       => 'required_with:parts|integer|exists:parts,id',
            'parts.*.quantity' => 'required_with:parts|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.exists'       => 'O mecânico selecionado não foi encontrado no sistema.',
            'status.in'            => 'Status inválido. Use: pending, in_progress, completed ou cancelled.',
            'parts.*.id.exists'    => 'Uma ou mais peças selecionadas não existem no catálogo.',
            'parts.*.quantity.min' => 'A quantidade de cada peça utilizada deve ser no mínimo 1.',
        ];
    }
}
