<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortUrlStoreRequest;
use App\Http\Requests\ShortUrlUpdateRequest;
use App\Http\Resources\ShortUrlResource;
use App\Models\Url;
use App\Services\ShortUrlShortenService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ShortUrlController extends Controller
{
    public function __construct(
        private ShortUrlShortenService $urlShortenService
    ) {  
    }

    public function store(ShortUrlStoreRequest $request): JsonResource
    {
        $this->urlShortenService->setData($request->urlStoreData);

        return new ShortUrlResource($this->urlShortenService->store());
    }

    public function update(ShortUrlUpdateRequest $request, Url $url): JsonResource
    {
        $this->urlShortenService->setData($request->urlUpdateData);

        try {
            $url = $this->urlShortenService->update($url);
        } catch (Exception $e) {
            response()->json(['message' => $e->getMessage()], 400);
        }

        return new ShortUrlResource($url);
    }

    public function destroy(Url $url): JsonResponse
    {
        $url->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function redirect(string $shortCode): RedirectResponse|JsonResponse
    {
        try {
            $url = Url::where(Url::SHORT_CODE, $shortCode)->firstOrFail();
        } catch (Exception $e) {
            return response()->json(['message' => 'Short Url not found'], 404);
        }

        return redirect()->away($url->long_url);
    }
}
