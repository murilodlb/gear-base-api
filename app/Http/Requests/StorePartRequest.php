<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para Criação de Peça (Part)
 *
 * Responsável por garantir que nenhuma peça seja cadastrada com dados inconsistentes,
 * estoque negativo ou código SKU duplicado.
 */
class StorePartRequest extends FormRequest
{
    /**
     * Autoriza a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para criação de peças.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Nome descritivo da peça (ex: "Pastilha de Freio Dianteira")
            'name'           => 'required|string|max:255',

            // SKU (Stock Keeping Unit): Código único da peça no estoque da oficina
            // 'unique:parts,sku' verifica se já existe registro com mesmo SKU na tabela 'parts'
            'sku'            => 'required|string|max:100|unique:parts,sku',

            // Quantidade inicial em estoque (deve ser um número inteiro >= 0)
            'stock_quantity' => 'required|integer|min:0',

            // Preço de custo da oficina (opcional, pode ser nulo se não informado)
            'cost_price'     => 'nullable|numeric|min:0',

            // Preço final cobrado do cliente na manutenção (obrigatório, numérico >= 0)
            'sale_price'     => 'required|numeric|min:0',
        ];
    }

    /**
     * Mensagens amigáveis em português.
     */
    public function messages(): array
    {
        return [
            'name.required'           => 'O nome da peça é obrigatório.',
            'sku.required'            => 'O código SKU é obrigatório.',
            'sku.unique'              => 'Já existe uma peça cadastrada com este código SKU.',
            'stock_quantity.required' => 'A quantidade em estoque é obrigatória.',
            'stock_quantity.min'      => 'A quantidade de estoque não pode ser negativa.',
            'sale_price.required'     => 'O preço de venda é obrigatório.',
            'sale_price.min'          => 'O preço de venda deve ser igual ou superior a zero.',
        ];
    }
}
