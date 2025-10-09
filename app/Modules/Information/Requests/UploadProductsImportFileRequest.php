<?php

namespace App\Modules\Information\Requests;

use App\Http\Requests\MainRequest;

class UploadProductsImportFileRequest extends MainRequest
{
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Fayl yuklanishi shart.',
            'file.file' => 'Fayl noto\'g\'ri formatda.',
            'file.mimes' => 'Faqat Excel fayl qabul qilinadi (xlsx, xls).',
            'file.max' => 'Fayl hajmi 10MB dan oshmasligi kerak.',
        ];
    }
}
