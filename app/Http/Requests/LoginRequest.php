<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para Autenticação (Login)
 *
 * Valida a presença e formato das credenciais de acesso antes de tentar verificar a senha no banco.
 */
class LoginRequest extends FormRequest
{
    /**
     * Qualquer visitante pode tentar autenticar (endpoint público).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para tentativa de login.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // E-mail válido e obrigatório
            'email'    => 'required|string|email',
            // Senha informada em texto plano enviada com segurança sob HTTPS
            'password' => 'required|string',
        ];
    }

    /**
     * Mensagens amigáveis em caso de campo faltante.
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'O e-mail é obrigatório para autenticar.',
            'email.email'       => 'Informe um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ];
    }
}
