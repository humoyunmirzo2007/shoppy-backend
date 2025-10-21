<?php

namespace App\Modules\Information\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductVariantRequest extends FormRequest
{
    /**
     * Request ni tasdiqlash huquqini tekshirish
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation qoidalari
     */
    public function rules(): array
    {
        $productVariantId = $this->route('productVariant');

        return [
            'product_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:products,id',
            ],
            'sku' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('product_variants', 'sku')->ignore($productVariantId),
            ],
            'price' => [
                'sometimes',
                'required',
                'numeric',
                'min:0',
            ],
            'stock' => [
                'sometimes',
                'required',
                'integer',
                'min:0',
            ],
            'image_url' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
                'url',
            ],
        ];
    }

    /**
     * Validation xabarlari
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Mahsulot majburiy',
            'product_id.integer' => 'Mahsulot ID si son bo\'lishi kerak',
            'product_id.exists' => 'Tanlangan mahsulot mavjud emas',
            'sku.required' => 'SKU kodi majburiy',
            'sku.string' => 'SKU kodi matn ko\'rinishida bo\'lishi kerak',
            'sku.max' => 'SKU kodi maksimal 255 ta belgi bo\'lishi kerak',
            'sku.unique' => 'Bu SKU kodi allaqachon mavjud',
            'price.required' => 'Narx majburiy',
            'price.numeric' => 'Narx raqam bo\'lishi kerak',
            'price.min' => 'Narx 0 dan kichik bo\'lishi mumkin emas',
            'stock.required' => 'Ombor miqdori majburiy',
            'stock.integer' => 'Ombor miqdori butun son bo\'lishi kerak',
            'stock.min' => 'Ombor miqdori 0 dan kichik bo\'lishi mumkin emas',
            'image_url.string' => 'Rasm URL matn ko\'rinishida bo\'lishi kerak',
            'image_url.max' => 'Rasm URL maksimal 500 ta belgi bo\'lishi kerak',
            'image_url.url' => 'Rasm URL to\'g\'ri formatda bo\'lishi kerak',
        ];
    }
}
