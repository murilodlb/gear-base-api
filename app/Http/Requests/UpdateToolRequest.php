<?php

namespace App\Http\Requests;

use App\Models\Tool;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request para Atualização de Ferramenta (Tool)
 *
 * Permite alteração de status operacional da ferramenta (ex: passar de 'available' para 'in_use'),
 * atualização de nome ou número de série.
 */
class UpdateToolRequest extends FormRequest
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
        $tool = $this->route('tool');
        $toolId = $tool instanceof Tool ? $tool->id : $tool;

        return [
            'name'          => 'sometimes|required|string|max:255',
            'serial_number' => [
                'sometimes',
                'nullable',
                'string',
                'max:100',
                Rule::unique('tools', 'serial_number')->ignore($toolId),
            ],
            'status'        => 'sometimes|required|string|in:available,in_use,maintenance',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'O nome da ferramenta não pode ser vazio quando informado.',
            'serial_number.unique' => 'Este número de série já está registrado para outra ferramenta.',
            'status.in'            => 'O status informado é inválido. Valores aceitos: available, in_use, maintenance.',
        ];
    }
}
