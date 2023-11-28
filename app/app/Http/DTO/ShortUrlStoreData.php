<?php

namespace App\Http\DTO;

use App\Http\DTO\ShortUrlDataInterface;
use App\Models\Url;

class ShortUrlStoreData implements ShortUrlDataInterface
{
    public function __construct(
        public readonly string $userId,
        public readonly string $title,
        public readonly string $longUrl,
        private ?string $shortCode,
        public readonly ?string $expiresAt,
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

        if (isset($this->expiresAt)) {
            $array[ShortUrl::EXPIRES_AT] = $this->expiresAt;
        }

        return $array;
    }
}