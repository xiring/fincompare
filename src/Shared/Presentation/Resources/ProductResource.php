<?php

namespace Src\Shared\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->image_url,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'attribute_highlights' => $this->attribute_highlights,
            'partner' => $this->when(
                $this->partner_id,
                function () {
                    // Ensure partner is loaded if relationship isn't loaded
                    if (!$this->relationLoaded('partner')) {
                        $this->loadMissing('partner');
                    }
                    return $this->partner ? new PartnerResource($this->partner) : null;
                }
            ),
            'product_category' => $this->whenLoaded('productCategory', function () {
                return [
                    'id' => $this->productCategory->id,
                    'name' => $this->productCategory->name,
                    'slug' => $this->productCategory->slug,
                ];
            }),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}

