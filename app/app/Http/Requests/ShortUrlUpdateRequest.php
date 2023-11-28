<?php

namespace App\Http\Requests;

use App\Http\DTO\ShortUrlUpdateData;
use Illuminate\Foundation\Http\FormRequest;

class ShortUrlUpdateRequest extends FormRequest
{
    public ShortUrlUpdateData $urlUpdateData;


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['string', 'max:255'],
            'long_url' => ['string', 'url', 'max:255'],
            'short_code' => [
                'regex:/^[a-z -]+$/',
                'max:50',
                'unique:urls,short_code'
            ],
            'expires_at' => ['date', 'after:now'],
        ];
    }

    public function passedValidation(): void
    {
        $this->urlUpdateData = new ShortUrlUpdateData(
            title: $this->get('title'),
            longUrl: $this->get('long_url'),
            shortCode: $this->get('short_code'),
            expiresAt: $this->get('expires_at'),
        );
    }
}
