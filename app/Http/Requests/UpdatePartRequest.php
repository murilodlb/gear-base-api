<?php

namespace App\Http\Requests;

use App\Models\Part;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request para Atualização de Peça (Part)
 *
 * Suporta operações completas (PUT) e parciais (PATCH).
 * Utiliza Rule::unique()->ignore() para não rejeitar requisições em que o SKU atual permaneça o mesmo.
 */
class UpdatePartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para atualização de peça.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Resgata a instância ou ID da peça recebida na rota /api/parts/{part}
        $part = $this->route('part');
        $partId = $part instanceof Part ? $part->id : $part;

        return [
            // 'sometimes': Só valida a regra se o campo estiver presente na requisição HTTP
            'name'           => 'sometimes|required|string|max:255',

            // Permite manter o próprio SKU da peça sem disparar erro de duplicidade
            'sku'            => [
                'sometimes',
                'required',
                'string',
                'max:100',
                Rule::unique('parts', 'sku')->ignore($partId),
            ],

            'stock_quantity' => 'sometimes|required|integer|min:0',
            'cost_price'     => 'nullable|numeric|min:0',
            'sale_price'     => 'sometimes|required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'O nome da peça não pode ser vazio.',
            'sku.required'            => 'O código SKU não pode ser vazio.',
            'sku.unique'              => 'Este código SKU já está sendo utilizado por outra peça.',
            'stock_quantity.min'      => 'A quantidade em estoque não pode ser negativa.',
            'sale_price.min'          => 'O preço de venda deve ser igual ou superior a zero.',
        ];
    }
}
