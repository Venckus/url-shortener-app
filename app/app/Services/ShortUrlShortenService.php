<?php

namespace App\Services;

use App\Http\DTO\ShortUrlDataInterface;
use App\Models\Url;

class ShortUrlShortenService
{
    private ShortUrlDataInterface $urlData;

    public function setData(ShortUrlDataInterface $urlData): self
    {
        $this->urlData = $urlData;

         return $this;
    }

    public function store(): Url
    {
        if (!$this->urlData->hasShortCode()) {
            $shortCode = $this->generateShortCode();
            $this->urlData->setShortCode($shortCode);
        }

        return Url::create($this->urlData->toArray());
    }

    public function update(Url $url): Url
    {
        $url->update($this->urlData->toArray());

        return $url->refresh();
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