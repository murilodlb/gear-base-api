<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * UserResource (API Resource para Usuário / Mecânico)
 *
 * No Laravel, um JsonResource atua como uma camada de transformação (Transformer).
 * Em vez de retornar o Model diretamente no controller (o que expõe a estrutura interna
 * da tabela do banco de dados), o Resource define um contrato explícito do JSON enviado ao cliente.
 *
 * Benefícios:
 * 1. Segurança: Campos sensíveis (como password, tokens) nunca vazam acidentalmente.
 * 2. Versionamento e Flexibilidade: Você pode renomear chaves sem precisar renomear colunas do banco.
 * 3. Formatação: Padronização de datas (ISO 8601) e números (float/int).
 */
class UserResource extends JsonResource
{
    /**
     * Transforma o recurso em um array serializável para JSON.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
