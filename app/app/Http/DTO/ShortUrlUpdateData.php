<?php

namespace App\Http\DTO;

use App\Http\DTO\ShortUrlDataInterface;
use App\Models\Url;


class ShortUrlUpdateData implements ShortUrlDataInterface
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $longUrl = null,
        public readonly ?string $shortCode = null,
        public readonly ?string $expiresAt = null,
    ) {
    }

    /**
     * @return string[][]
     */
    public function toArray(): array
    {
        $dataArray = [];

        if (isset($this->title)) {
            $dataArray[Url::TITLE] = $this->title;
        }
        if (isset($this->longUrl)) {
            $dataArray[Url::LONG_URL] = $this->longUrl;
        }
        if (isset($this->shortCode)) {
            $dataArray[Url::SHORT_CODE] = $this->shortCode;
        }
        if (isset($this->expiresAt)) {
            $dataArray[Url::EXPIRES_AT] = $this->expiresAt;
        }

        return $dataArray;
    }
}