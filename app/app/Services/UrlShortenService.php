<?php

namespace App\Services;

use App\Http\DTO\UrlStoreData;
use App\Models\Url;

class UrlShortenService
{
    public function __construct(
        private readonly UrlStoreData $storeUrlData,
    ) {
    }

    public function store(): Url
    {
        if (!$this->storeUrlData->hasShortCode()) {
            $shortCode = $this->generateShortCode();
            $this->storeUrlData->setShortCode($shortCode);
        }

        return Url::create($this->storeUrlData->toArray());
    }

    private function generateShortCode(int $length = 24): string
    {
        $vowels = ['a','e','i','o','u', 'y'];
        $consonants = [
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'z'
        ];  
        
        $string = '';
        $i = 0;
        while (strlen($string) < $length) {
            if ($i % 2 == 0) {
                $string .= $consonants[rand(0,18)];
            } else {
                $string .= $vowels[rand(0,5)];
            }

            if ($i % 4 === 3 && $i < ($length - 7)) {
                $string .= '-';
            }
            $i++;
        }
    
        return $string;
    }
}