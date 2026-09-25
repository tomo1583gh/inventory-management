<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;

class DemoUserItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * デモユーザーは商品を削除できない
     */
    public function test_demo_user_cannot_delete_item(): void

    {
        config([
            'app.demo_user_email' => 'demo@example.com',
        ]);

        $demoUser = User::factory()->create([
            'email' => 'demo@example.com',
        ]);

        $item = Item::factory()->create();

        $response = $this
            ->actingAs($demoUser)
            ->delete(route('items.destroy', $item));

        $response->assertForbidden();

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
        ]);
    }

    /**
     * 通常ユーザーは商品を削除できる
     */
    public function test_normal_user_can_delete_item(): void
    {
        config([
            'app.demo_user_email' => 'demo@example.com',
        ]);

        $normalUser = User::factory()->create([
            'email' => 'user@example.com',
        ]);

        $item = Item::factory()->create();

        $response = $this
            ->actingAs($normalUser)
            ->delete(route('items.destroy', $item));

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseMissing('items', [
            'id' => $item->id,
        ]);
    }

    /**
     * デモユーザーは商品CSVをインポートできない
     */
    public function test_demo_user_cannot_import_item_csv(): void
    {
        config([
            'app.demo_user_email' => 'demo@example.com',
        ]);

        $demoUser = User::factory()->create([
            'email' => 'demo@example.com',
        ]);

        $csv = UploadedFile::fake()->create(
            'items.csv',
            10,
            'text/csv'
        );

        $response = $this
            ->actingAs($demoUser)
            ->post(route('items.import.store'), [
                'csv_file' => $csv,
            ]);

        $response->assertForbidden();
    }
}
