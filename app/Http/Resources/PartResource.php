<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * PartResource (API Resource para Peças Automotivas)
 *
 * Transforma a entidade Part em JSON, assegurando conversões de tipos estritas
 * (float para preços e int para estoque) e permitindo enriquecer o payload com dados
 * da tabela pivot quando a peça for carregada dentro do contexto de uma Manutenção.
 */
class PartResource extends JsonResource
{
    /**
     * Transforma a peça em um formato JSON amigável e consistente.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'sku'            => $this->sku,
            'stock_quantity' => (int) $this->stock_quantity,
            'cost_price'     => $this->cost_price !== null ? (float) $this->cost_price : null,
            'sale_price'     => (float) $this->sale_price,

            // Informações condicionais da Tabela Pivot:
            // $this->whenPivotLoaded() só inclui essas chaves quando o modelo 'Part'
            // for resgatado via relacionamento Muitos-para-Muitos ($maintenance->parts).
            // Em listagens normais de /api/parts, essas chaves NÃO serão incluídas.
            'quantity_used'  => $this->whenPivotLoaded('maintenance_part', function () {
                return (int) $this->pivot->quantity;
            }),
            'subtotal'       => $this->whenPivotLoaded('maintenance_part', function () {
                return round((float) $this->sale_price * (int) $this->pivot->quantity, 2);
            }),

            'created_at'     => $this->created_at?->toIso8601String(),
            'updated_at'     => $this->updated_at?->toIso8601String(),
        ];
    }
}
