<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlStoreRequest;
use App\Http\Resources\UrlResource;
use App\Services\UrlShortenService;
use Illuminate\Http\Request;

class UrlController extends Controller
{
    public function store(UrlStoreRequest $request)
    {
        $shortenUrlService = new UrlShortenService($request->urlStoreData);

        return new UrlResource($shortenUrlService->store());
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
