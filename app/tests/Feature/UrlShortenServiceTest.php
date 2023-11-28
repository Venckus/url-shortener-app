<?php

namespace Tests\Feature;

use App\Http\DTO\UrlStoreData;
use App\Http\DTO\UrlUpdateData;
use App\Models\Url;
use App\Models\User;
use App\Services\UrlShortenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UrlShortenServiceTest extends TestCase
{
    use RefreshDatabase;

    private UrlShortenService $urlShortenService;


    protected function setUp(): void
    {
        parent::setUp();

        $this->urlShortenService = new UrlShortenService();
    }

    public function testShouldGenerateShortCode(): void
    {
        $user = User::factory()->create();

        $urlDTO = new UrlStoreData(
            userId: $user->id,
            title: 'My Awesome Url',
            longUrl: 'https://www.super-long.com/awesome/url/g871t2396',
            shortCode: null,
        );

        $this->urlShortenService->setData($urlDTO);

        $url = $this->urlShortenService->store();

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

        $this->urlShortenService->setData($urlDTO);

        $url = $this->urlShortenService->store();

        $this->assertEquals(
            expected: 'my-awesome-url',
            actual: $url->toArray()['short_code']
        );
        $this->assertDatabaseHas(
            table: 'urls', 
            data: ['short_code' => 'my-awesome-url']
        );
    }

    /**
     * @dataProvider newFieldsProvider
     */
    public function testShouldUpdateUrl(UrlUpdateData $urlDTO, string $DTOparam, string $dbParam): void
    {
        $user = User::factory()->create();

        $url = Url::create([
            'user_id' => $user->id,
            'title' => 'My old Url',
            'long_url' => 'https://www.super-long.com/old/url/g871t2396',
            'short_code' => 'my-old-url',
        ]);

        $this->urlShortenService->setData($urlDTO);

        $url = $this->urlShortenService->update($url);

        $this->assertEquals(
            expected: $urlDTO->{$DTOparam},
            actual: $url->toArray()[$dbParam]
        );
    }

    /**
     * @ return array<string, array<UrlUpdateData|string>>
     * @return string[][UrlUpdateData|string]
     */
    public static function newFieldsProvider(): array
    {
        return [
            'new title' => [
                new UrlUpdateData(title: 'My new Url'),
                'title',
                'title',
            ],
            'new long_url' => [
                new UrlUpdateData(longUrl: 'My new Url'),
                'longUrl',
                'long_url',
            ],
            'new short_code' => [
                new UrlUpdateData(shortCode: 'my-new-url'),
                'shortCode',
                'short_code',
            ],
            'new expires_at' => [
                new UrlUpdateData(expiresAt: '2024-12-12T00:00:00.000000Z'),
                'expiresAt',
                'expires_at',
            ],
        ];
    }
}
