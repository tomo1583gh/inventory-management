<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class DemoUserStockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * デモユーザーは入出庫CSVをインポートできない
     */
    public function test_demo_user_cannot_import_stock_csv(): void
    {
        config([
            'app.demo_user_email' => 'demo@example.com',
        ]);

        $demoUser = User::factory()->create([
            'email' => 'demo@example.com',
        ]);

        $csv = UploadedFile::fake()->create(
            'stocks.csv',
            10,
            'text/csv'
        );

        $response = $this
            ->actingAs($demoUser)
            ->post(route('stocks.import.store'), [
                'csv_file' => $csv,
            ]);

        $response->assertForbidden();
    }
}
