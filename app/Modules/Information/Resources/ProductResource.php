<?php

namespace App\Modules\Information\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $attributes = $this->whenLoaded('productAttributes', function () {
            if (! $this->productAttributes) {
                return [];
            }

            return $this->productAttributes->map(function ($productAttribute) {
                $attributeValue = $productAttribute->attributeValue;
                $attribute = $attributeValue && $attributeValue->attribute ? $attributeValue->attribute : null;

                return [
                    'id' => $productAttribute->id,
                    'attribute_value' => $attributeValue ? [
                        'id' => $attributeValue->id,
                        'value_uz' => $attributeValue->value_uz,
                        'value_ru' => $attributeValue->value_ru,
                        'attribute_id' => $attributeValue->attribute_id,
                        'attribute' => $attribute ? [
                            'id' => $attribute->id,
                            'name_uz' => $attribute->name_uz,
                            'name_ru' => $attribute->name_ru,
                        ] : null,
                    ] : null,
                ];
            })->values()->all();
        }, []);

        // Images va main_image uchun to'liq URL yaratish
        $images = null;
        if ($this->images) {
            $decodedImages = json_decode($this->images, true);
            if (is_array($decodedImages)) {
                $images = array_map(function ($image) {
                    return asset('storage/'.$image);
                }, $decodedImages);
            }
        }

        $mainImage = null;
        if ($this->main_image) {
            $decodedMainImage = json_decode($this->main_image, true);
            if (is_string($decodedMainImage)) {
                $mainImage = asset('storage/'.$decodedMainImage);
            }
        }

        return [
            'id' => $this->id,
            'name_uz' => $this->name_uz,
            'name_ru' => $this->name_ru,
            'description_uz' => $this->description_uz,
            'description_ru' => $this->description_ru,
            'sku' => $this->sku,
            'unit' => $this->unit,
            'price' => (float) $this->price,
            'wholesale_price' => (float) $this->wholesale_price,
            'markup' => (float) $this->markup,
            'residue' => (float) $this->residue,
            'is_active' => (bool) $this->is_active,
            'images' => $images,
            'main_image' => $mainImage,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'name_uz' => $this->category->name_uz,
                    'name_ru' => $this->category->name_ru,
                ] : null;
            }),
            'brand' => $this->whenLoaded('brand', function () {
                return $this->brand ? [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name,
                ] : null;
            }),
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'product_group_id' => $this->product_group_id,
            'attributes' => $attributes,
        ];
    }
}
