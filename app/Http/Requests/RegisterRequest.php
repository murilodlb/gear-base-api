<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para Registro de Novos Usuários
 *
 * No padrão REST com Laravel, Form Requests são classes dedicadas para isolar
 * a lógica de validação e autorização fora dos controladores (Controllers).
 * Isso mantém os controladores "magros" (Thin Controllers) e com responsabilidade única.
 */
class RegisterRequest extends FormRequest
{
    /**
     * Determina se o usuário atual tem autorização para fazer esta requisição.
     *
     * Em endpoints públicos de cadastro, retornamos true.
     * Caso o sistema exigisse perfil de 'administrador', poderíamos checar aqui:
     * return $this->user() && $this->user()->isAdmin();
     * Se retornar false, o Laravel automaticamente responde com HTTP 403 Forbidden.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define as regras de validação aplicadas aos dados da requisição.
     *
     * Se qualquer regra falhar, o Laravel aborta a execução do método no Controller
     * e retorna automaticamente uma resposta HTTP 422 (Unprocessable Content) com os erros em JSON.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'required': o campo não pode ser omitido nem nulo
            // 'max:255': tamanho máximo aceito no banco
            'name'     => 'required|string|max:255',

            // 'email': valida se segue o formato padrão RFC de e-mail (user@domain.com)
            // 'unique:users,email': consulta o banco e impede e-mails já cadastrados na tabela 'users'
            'email'    => 'required|string|email|max:255|unique:users,email',

            // 'confirmed': exige que o cliente envie o campo 'password_confirmation' idêntico ao 'password'
            // 'min:8': comprimento mínimo para garantir segurança da senha
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Mensagens de validação customizadas (Opcional, mas excelente para APIs amigáveis em PT-BR).
     */
    public function messages(): array
    {
        return [
            'name.required'      => 'O nome do usuário é obrigatório.',
            'email.required'     => 'O endereço de e-mail é obrigatório.',
            'email.email'        => 'Por favor, informe um endereço de e-mail válido.',
            'email.unique'       => 'Este e-mail já está em uso por outro usuário.',
            'password.required'  => 'A senha é obrigatória.',
            'password.min'       => 'A senha deve conter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação de senha não confere.',
        ];
    }
}
