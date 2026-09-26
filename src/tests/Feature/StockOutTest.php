<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\StockLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDATABASE;
use Tests\TestCase;

class StockOutTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_stock_out_more_than_current_stock(): void
    {
        $user = User::factory()->create();

        $item = Item::factory()->create();

        StockLog::create([
            'item_id' => $item->id,
            'type' => 'in',
            'qty' => 10,
            'user_id' => $user->id,
            'acted_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('stocks.out.store'),[
                'item_id' => $item->id,
                'qty' => 11,
                'acted_at' => now()->format('Y-m-d H:i:s'),
                'note' => null
            ]);
        $response->assertSessionHasErrors('qty');

        $this->assertDatabaseMissing('stock_logs' , [
            'item_id' => $item->id,
            'type' => 'out',
            'qty' => 11,
        ]);
    }
}
