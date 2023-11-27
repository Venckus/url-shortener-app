<?php

namespace App\Http\Requests;

use App\Http\DTO\UrlStoreData;
use App\Models\Url;
use Illuminate\Foundation\Http\FormRequest;

class UrlStoreRequest extends FormRequest
{
    public UrlStoreData $urlStoreData;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:App\Models\User,id'],
            'title' => ['required', 'string', 'max:255'],
            'long_url' => ['required', 'string', 'url', 'max:255'],
            'short_code' => [
                'regex:/^[a-z -]+$/',
                'max:50',
                'unique:urls,short_code'
            ],
        ];
    }

    public function passedValidation(): void
    {
        $this->urlStoreData = new UrlStoreData(
            userId: $this->get('user_id'),
            title: $this->get('title'),
            longUrl: $this->get('long_url'),
            shortCode: $this->get('short_code'),
        );
    }
}
