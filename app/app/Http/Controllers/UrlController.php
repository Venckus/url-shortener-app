<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlStoreRequest;
use App\Http\Requests\UrlUpdateRequest;
use App\Http\Resources\UrlResource;
use App\Models\Url;
use App\Models\User;
use App\Services\UrlShortenService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class UrlController extends Controller
{
    public function __construct(
        private UrlShortenService $urlShortenService
    ) {  
    }

    public function store(UrlStoreRequest $request): JsonResource
    {
        $this->urlShortenService->setData($request->urlStoreData);

        return new UrlResource($this->urlShortenService->store());
    }

    public function update(UrlUpdateRequest $request, Url $url): JsonResource
    {
        $this->urlShortenService->setData($request->urlUpdateData);

        try {
            $url = $this->urlShortenService->update($url);
        } catch (Exception $e) {
            response()->json(['message' => $e->getMessage()], 400);
        }

        return new UrlResource($url);
    }

    public function destroy(Url $url): JsonResponse
    {
        $url->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
