<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UrlModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider shouldCreateUrlDbRecordsDataProvider
     */
    public function testShouldCreateUrlDbRecords(array $urlDbTableData): void
    {
        $user = User::factory()->create();

        $urlDbTableData['user_id'] = $user->id;
        $url = Url::create($urlDbTableData);

        $urlDbTableData['id'] = $url->id;

        $this->assertDatabaseCount('urls', 1);
        $this->assertDatabaseHas('urls', $urlDbTableData);
    }

    /**
     * @return string[][]
     */
    public static function shouldCreateUrlDbRecordsDataProvider(): array
    {
        return [
            'without expires_at' => [[
                'title' => 'Google random path',
                'long_url' => 'https://www.google.com/random/path',
                'short_url' => 'https://my.random.sh.ort/',
            ]],
            'with expires_at' => [[
                'title' => 'Google random path',
                'long_url' => 'https://www.google.com/random/path',
                'short_url' => 'https://my.random.sh.ort/',
                'expires_at' => Carbon::create(year: 2025),
            ]],
        ];
    }
}
