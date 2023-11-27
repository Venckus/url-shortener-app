<?php

namespace App\Http\DTO;

use App\Models\Url;

class UrlStoreData
{
    public function __construct(
        public readonly int $userId,
        public readonly string $title,
        public readonly string $longUrl,
        private ?string $shortCode,
    ) {
    }

    public function hasShortCode(): bool
    {
        return isset($this->shortCode);
    }

    public function setShortCode(string $shortCode): void
    {
        $this->shortCode = $shortCode;
    }

    /**
     * @return string[][]
     */
    public function toArray(): array
    {
        return [
            Url::USER_ID => $this->userId,
            Url::TITLE => $this->title,
            Url::LONG_URL => $this->longUrl,
            Url::SHORT_CODE => $this->shortCode,
        ];
    }
}