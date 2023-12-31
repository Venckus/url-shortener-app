<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShortUrlControllerTest extends TestCase
{
    use RefreshDatabase;


    public function testShouldCreateUrl(): void
    {
        $user = User::factory()->create();

        $response = $this->json(
            method: 'POST',
            uri: '/api/urls',
            data: [
                'user_id' => $user->id,
                'title' => 'My Awesome Url',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'myawesomeurl',
            ]
        );

        $response->assertStatus(201);
    }

    /**
     * @dataProvider invalidFieldsProvider
     */
    public function testShouldValidateFields(array $urlDbTableData): void
    {
        User::factory()->create();

        $response = $this->json(
            method: 'POST',
            uri: '/api/urls',
            data: $urlDbTableData
        );

        $response->assertStatus(422);
    }

    /**
     * @return string[][]
     */
    public static function invalidFieldsProvider(): array
    {
        return [
            'user_id missing' => [[
                'title' => 'My Awesome Url',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'my-awesome-url',
            ]],
            'title missing' => [[
                'user_id' => 1,
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'my-awesome-url',
            ]],
            'long_url missing' => [[
                'user_id' => 1,
                'title' => 'My Awesome Url',
                'short_code' => 'my-awesome-url',
            ]],
            'wrong short_code with dots' => [[
                'user_id' => 1,
                'title' => 'My Awesome Url',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'my.awesome.url',
            ]],
            'wrong short_code with numbers' => [[
                'user_id' => 1,
                'title' => 'My Awesome Url',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'my1awesome2url',
            ]],
            'wrong short_code with special chars' => [[
                'user_id' => 1,
                'title' => 'My Awesome Url',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'my$awesome<?url',
            ]],
        ];
    }

    public function testShouldUpdateShortCode(): void
    {
        $user = User::factory()->create();

        $url = Url::create([
            'user_id' => $user->id,
            'title' => 'My Awesome Url',
            'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
            'short_code' => 'myawesomeurl',
        ]);

        $response = $this->json(
            method: 'PATCH',
            uri: "/api/urls/{$url->id}",
            data: [
                'title' => 'New Url Title',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'new-awesome-url',
            ]
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas(
            table: 'urls',
            data: [
                'id' => $url->id,
                'title' => 'New Url Title',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'new-awesome-url',
            ]
        );
    }

    public function testShouldNotUpdateWhenInvalidUrlId(): void
    {
        $user = User::factory()->create();

        $response = $this->json(
            method: 'PATCH',
            uri: '/api/urls/invalid-url-id',
            data: [
                'title' => 'New Url Title',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'new-awesome-url',
            ],
        );

        $response->assertStatus(404);
    }

    public function testShouldNotUpdateWhenSameShortCode(): void
    {
        $user = User::factory()->create();

        $url = Url::create([
            'user_id' => $user->id,
            'title' => 'My Awesome Url',
            'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
            'short_code' => 'myawesomeurl',
        ]);

        $response = $this->json(
            method: 'PATCH',
            uri: "/api/urls/{$url->id}",
            data: [
                'title' => 'New Url Title',
                'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
                'short_code' => 'myawesomeurl',
            ],
        );

        $response->assertStatus(422);
    }

    public function testShouldNotUpdateWhenExpiresAtIsInThePast(): void
    {
        $user = User::factory()->create();

        $url = Url::create([
            'user_id' => $user->id,
            'title' => 'My Awesome Url',
            'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
            'short_code' => 'myawesomeurl',
        ]);

        $response = $this->json(
            method: 'PATCH',
            uri: "/api/urls/{$url->id}",
            data: [
                'expires_at' => '2020-01-01 00:00:00',
            ],
        );

        $response->assertStatus(422);
    }

    public function testShouldDestroyUrl(): void
    {
        $user = User::factory()->create();

        $url = Url::create([
            'user_id' => $user->id,
            'title' => 'My Awesome Url',
            'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
            'short_code' => 'myawesomeurl',
        ]);

        $response = $this->json(
            method: 'DELETE',
            uri: "/api/urls/{$url->id}",
        );

        $response->assertStatus(204);

        $this->assertDatabaseCount(
            table: 'urls',
            count: 1,
        );
        $this->assertDatabaseHas(
            table: 'urls',
            data: [
                'id' => $url->id,
            ]
        );
        $url->refresh();
        $this->assertNotNull($url->deleted_at);
    }

    public function testShouldRedirectToLongUrl(): void
    {
        $user = User::factory()->create();

        $url = Url::create([
            'user_id' => $user->id,
            'title' => 'My Awesome Url',
            'long_url' => 'https://www.super-long.com/awesome/url/g871t2396',
            'short_code' => 'myawesomeurl',
        ]);

        $response = $this->get("/{$url->short_code}");

        $response->assertStatus(302);
        $response->assertRedirect($url->long_url);
    }

    public function testShouldNotRedirectWhenInvalidShortCode(): void
    {
        $response = $this->get('/invalid-short-code');

        $response->assertStatus(404);
        $response->assertJson(['message' => 'Short Url not found']);
    }
}
