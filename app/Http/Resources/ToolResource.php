<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * ToolResource (API Resource para Ferramentas)
 *
 * Padroniza os campos expostos pela entidade Tool (id, nome, número de série, status operacional).
 */
class ToolResource extends JsonResource
{
    /**
     * Transforma a ferramenta em array serializável.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'serial_number' => $this->serial_number,
            'status'        => $this->status,
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
