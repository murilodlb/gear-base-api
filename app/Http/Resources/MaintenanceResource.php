<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * MaintenanceResource (API Resource para Ordens de Manutenção)
 *
 * Demonstra recursos avançados do Laravel JsonResource:
 * 1. Aninhamento de Recursos (Nested Resources): Incorpora UserResource e coleção de PartResource.
 * 2. Carregamento Condicional ($this->whenLoaded): Só inclui o mecânico ou as peças se o Controller
 *    tiver feito o Eager Loading prévio (ex: Maintenance::with(['user', 'parts'])).
 *    Isso evita o catastrófico problema de "N+1 Queries" (fazer uma query extra por linha retornada).
 * 3. Cálculos Dinâmicos: Computa o total das peças utilizadas e o custo total da ordem (mão de obra + peças).
 */
class MaintenanceResource extends JsonResource
{
    /**
     * Transforma a manutenção em JSON formatado.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Se as peças estiverem carregadas via Eager Loading, calcula a soma das peças
        $partsCost = null;
        if ($this->relationLoaded('parts')) {
            $partsCost = $this->parts->sum(function ($part) {
                return (float) $part->sale_price * (int) ($part->pivot->quantity ?? 1);
            });
            $partsCost = round($partsCost, 2);
        }

        $laborCost = (float) $this->labor_cost;
        $totalCost = $partsCost !== null ? round($laborCost + $partsCost, 2) : null;

        return [
            'id'            => $this->id,
            'user_id'       => $this->user_id,

            // Aninhamento condicional do mecânico responsável
            'mechanic'      => new UserResource($this->whenLoaded('user')),

            'vehicle_plate' => $this->vehicle_plate,
            'vehicle_model' => $this->vehicle_model,
            'description'   => $this->description,
            'status'        => $this->status,

            'labor_cost'    => $laborCost,
            'parts_cost'    => $partsCost,
            'total_cost'    => $totalCost,

            // Aninhamento condicional da lista de peças utilizadas com quantidades do pivot
            'parts'         => PartResource::collection($this->whenLoaded('parts')),

            'entry_date'    => $this->entry_date?->toIso8601String(),
            'delivery_date' => $this->delivery_date?->toIso8601String(),
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
