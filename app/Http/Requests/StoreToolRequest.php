<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para Cadastro de Ferramenta (Tool)
 *
 * Garante que ferramentas da oficina tenham nome, número de série válido (caso possuam)
 * e um estado inicial coerente.
 */
class StoreToolRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para ferramentas.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Nome ou descrição da ferramenta (ex: "Torquímetro Digital 1/2")
            'name'          => 'required|string|max:255',

            // Número de série: pode ser nulo para ferramentas simples (ex: chave de fenda),
            // mas caso informado deve ser único no sistema para rastreabilidade
            'serial_number' => 'nullable|string|max:100|unique:tools,serial_number',

            // Status da ferramenta: restrito a uma lista pré-definida de estados permitidos
            // 'in:available,in_use,maintenance': impede inserção de status inválidos
            'status'        => 'sometimes|string|in:available,in_use,maintenance',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'O nome da ferramenta é obrigatório.',
            'serial_number.unique' => 'Já existe uma ferramenta com este número de série cadastrada.',
            'status.in'            => 'O status informado é inválido. Valores aceitos: available, in_use, maintenance.',
        ];
    }
}
