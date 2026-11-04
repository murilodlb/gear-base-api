<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form Request para Atualização de Usuário/Mecânico
 *
 * Suporta tanto o método PUT (atualização completa) quanto PATCH (atualização parcial).
 */
class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para atualização.
     *
     * Utilizamos 'sometimes' para permitir envio parcial de campos (método PATCH).
     * Utilizamos Rule::unique()->ignore(...) para evitar falso-positivo de e-mail duplicado
     * caso o usuário mantenha o mesmo endereço já salvo no banco.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Obtemos o ID do usuário diretamente da rota REST (/api/users/{user})
        // O Laravel Route Model Binding pode injetar tanto o Model User quanto o ID numérico.
        $userId = $this->route('user') instanceof \App\Models\User
            ? $this->route('user')->id
            : $this->route('user');

        return [
            // 'sometimes': Apenas valida a regra se o campo estiver presente no payload JSON
            'name'     => 'sometimes|required|string|max:255',

            // Ignora o ID deste próprio registro na checagem de unicidade da coluna 'email'
            'email'    => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            // Senha opcional na atualização (apenas se o cliente desejar alterar)
            'password' => 'sometimes|nullable|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'O nome não pode ser vazio quando informado.',
            'email.required' => 'O e-mail não pode ser vazio quando informado.',
            'email.unique'   => 'Este e-mail já pertence a outro usuário.',
            'password.min'   => 'A nova senha deve ter no mínimo 8 caracteres.',
        ];
    }
}
