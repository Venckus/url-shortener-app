<?php

namespace Tests\Feature;

use App\Http\DTO\UrlStoreData;
use App\Models\User;
use App\Services\UrlShortenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortenServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldGenerateShortCode(): void
    {
        $user = User::factory()->create();

        $urlDTO = new UrlStoreData(
            userId: $user->id,
            title: 'My Awesome Url',
            longUrl: 'https://www.super-long.com/awesome/url/g871t2396',
            shortCode: null,
        );
        $service = new UrlShortenService($urlDTO);

        $url = $service->store();

        $this->assertNotFalse($urlDTO->hasShortCode());
        $this->assertNotEmpty($url->toArray()['short_code']);
    }

    public function testShouldStoreProvidedShortCode(): void
    {
        $user = User::factory()->create();

        $urlDTO = new UrlStoreData(
            userId: $user->id,
            title: 'My Awesome Url',
            longUrl: 'https://www.super-long.com/awesome/url/g871t2396',
            shortCode: 'my-awesome-url',
        );
        $service = new UrlShortenService($urlDTO);

        $url = $service->store();

        $this->assertEquals('my-awesome-url', $url->toArray()['short_code']);
        $this->assertDatabaseHas('urls', [
            'short_code' => 'my-awesome-url',
        ]);
    }
}
