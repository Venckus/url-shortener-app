<?php

namespace App\Http\DTO;

use App\Http\DTO\ShortUrlDataInterface;
use Illuminate\Support\Str;


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

        foreach (get_object_vars($this) as $key => $value) {
            if (isset($value)) {
                $dataArray[Str::snake($key)] = $value;
            }
        }

        return $dataArray;
    }
}