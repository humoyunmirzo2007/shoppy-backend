<?php

namespace App\Modules\Information\Controllers;

use App\Helpers\Response;
use App\Modules\Information\Requests\TranslateRequest;
use App\Modules\Information\Services\TranslateService;
use Illuminate\Http\JsonResponse;

class TranslateController
{
    public function __construct(protected TranslateService $translateService) {}

    /**
     * Matnni tarjima qilish
     */
    public function translate(TranslateRequest $request): JsonResponse
    {
        $data = $request->validated();

        $text = $data['text'];
        $source = $data['source'] ?? 'uz';
        $target = $data['target'] ?? 'ru';

        $result = $this->translateService->translate($text, $source, $target);

        if ($result['success']) {
            return Response::success($result['data'], $result['message']);
        }

        return Response::error($result['message']);
    }
}
