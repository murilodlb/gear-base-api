<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para Criação de Usuário/Mecânico via CRUD Administrativo
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Determina se o usuário logado possui autorização para criar novos colaboradores.
     */
    public function authorize(): bool
    {
        // Como o endpoint está protegido pelo middleware 'auth:sanctum', podemos autorizar requisições autenticadas.
        return true;
    }

    /**
     * Regras de validação para a inserção de um novo usuário.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'O nome do usuário é obrigatório.',
            'email.required'    => 'O e-mail é obrigatório.',
            'email.unique'      => 'Este e-mail já está em uso.',
            'password.required' => 'A senha inicial é obrigatória.',
            'password.min'      => 'A senha deve conter no mínimo 8 caracteres.',
        ];
    }
}
