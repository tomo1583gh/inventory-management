<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use App\Models\StockLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログインユーザーが入庫を登録できる
     */
    public function test_authenticated_user_can_stock_in(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '農業資材',
        ]);

        $item = Item::create([
            'category_id' => $category->id,
            'name' => 'テスト商品',
            'sku' => 'STOCK-001',
            'unit' => '個',
            'minimum_stock' => 5,
            'note' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('stocks.in.store'), [
                'item_id' => $item->id,
                'type' => 'in',
                'qty' => 10,
                'user_id' => $user->id,
                'note' => 'テスト入庫',
                'acted_at' => '2026-10-05 09:00:00',
            ]);

        $response->assertRedirect(route('stocks.logs'));

        $stockLog = StockLog::where('item_id', $item->id)
            ->where('type', 'in')
            ->first();

        $this->assertNotNull($stockLog);
        $this->assertEquals(10, $stockLog->qty);
        $this->assertEquals($user->id, $stockLog->user_id);
        $this->assertEquals('テスト入庫', $stockLog->note);
    }

    /**
     * 入庫した数量が現在庫に反映される
     */
    public function test_stock_in_quantity_is_reflected_in_current_stock(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '肥料',
        ]);

        $item = Item::create([
            'category_id' => $category->id,
            'name' => '現在在庫確認商品',
            'sku' => 'STOCK-002',
            'unit' => '袋',
            'minimum_stock' => 5,
            'note' => null,
        ]);

        StockLog::create([
            'item_id' => $item->id,
            'type' => 'in',
            'qty' => 10,
            'user_id' => $user->id,
            'note' => '現在庫確認用',
            'acted_at' => '2026-10-05 09:00:00',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('stocks.index'));

        $response->assertOk();

        $response->assertSee('現在在庫確認商品');
        $response->assertSee('STOCK-002');
        $response->assertSee('10');
    }

    /**
     * ログインユーザーが在庫のは範囲内で出庫を登録できる
     */
    public function test_authenticated_user_can_stock_out(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '農業資材',
        ]);

        $item = Item::create([
            'category_id' => $category->id,
            'name' => '出庫テスト商品',
            'sku' => 'STOCK-003',
            'unit' => '個',
            'minimum_stock' => 5,
            'note' => null,
        ]);

        // 先に10個入庫しておく
        StockLog::create([
            'item_id' => $item->id,
            'type' => 'in',
            'qty' => 10,
            'user_id' => $user->id,
            'note' => '出庫テスト用入庫',
            'acted_at' => '2026-10-05 09:00:00',
        ]);

        // 3個出庫する
        $response = $this
            ->actingAs($user)
            ->post(route('stocks.out.store'), [
                'item_id' => $item->id,
                'qty' => 3,
                'note' => 'テスト出庫',
                'acted_at' => '2026-10-05 09:00:00',
            ]);

        $response->assertRedirect(route('stocks.logs'));

        $stockLog = StockLog::where('item_id', $item->id)
            ->where('type', 'out')
            ->first();

        $this->assertNotNull($stockLog);
        $this->assertEquals(3,$stockLog->qty);
        $this->assertEquals($user->id, $stockLog->user_id);
        $this->assertEquals('テスト出庫', $stockLog->note);
    }

    /**
     * 出庫した数量が現在庫から減算される
     */
    public function test_stock_out_quantity_is_reflected_in_current_stock(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '肥料',
        ]);

        $item = Item::create([
            'category_id' => $category->id,
            'name' => '在庫減算確認商品',
            'sku' => 'STOCK-004',
            'unit' => '袋',
            'minimum_stock' => 5,
            'note' => null,
        ]);

        // 10個入庫
        StockLog::create([
            'item_id' => $item->id,
            'type' => 'in',
            'qty' => 10,
            'user_id' => $user->id,
            'note' => '入庫',
            'acted_at' => '2026-10-05 09:00:00',
        ]);

        //3個出庫
        StockLog::create([
            'item_id' => $item->id,
            'type' => 'out',
            'qty' => 3,
            'user_id' => $user->id,
            'note' => '出庫',
            'acted_at' => '2026-10-05 09:00:00',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('stocks.index'));

        $response->assertOk();

        $response->assertSee('在庫減算確認商品');
        $response->assertSee('STOCK-004');
        $response->assertSee('7');
    }

    /**
     * ログインユーザーが入出庫履歴を確認できる
     */
    public function test_authenticated_user_can_view_stock_logs(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '農業資材',
        ]);

        $item = Item::create([
            'category_id' => $category->id,
            'name' => '履歴確認商品',
            'sku' => 'STOCK-005',
            'unit' => '個',
            'minimum_stock' => 5,
            'note' => null, 
        ]);

        StockLog::create([
            'item_id' => $item->id,
            'type' => 'in',
            'qty' => 10,
            'user_id' => $user->id,
            'note' => '履歴確認用入庫',
            'acted_at' => '2026-10-05 09:00:00',
        ]);

        StockLog::create([
            'item_id' => $item->id,
            'type' => 'out',
            'qty' => 3,
            'user_id' => $user->id,
            'note' => '履歴確認用出庫',
            'acted_at' => '2026-10-05 09:00:00',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('stocks.logs'));

        $response->assertOk();

        $response->assertSee('履歴確認商品');
        $response->assertSee('履歴確認用入庫');
        $response->assertSee('履歴確認用出庫');
    }

    /**
     * 特定商品の入出庫履歴だけを確認できる
     */
    public function test_authenticated_user_can_view_item_stock_logs(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '農業資材',
        ]);

        $itemA = Item::create([
            'category_id' => $category->id,
            'name' => '履歴商品A',
            'sku' => 'STOCK-006',
            'unit' => '個',
            'minimum_stock' => 5,
            'note' => null,
        ]);

        $itemB = Item::create([
            'category_id' => $category->id,
            'name' => '履歴商品B',
            'sku' => 'STOCK-007',
            'unit' => '個',
            'minimum_stock' => 5,
            'note' => null,
        ]);

        // 商品Aの履歴
        StockLog::create([
            'item_id' => $itemA->id,
            'type' => 'in',
            'qty' => 10,
            'user_id' => $user->id,
            'note' => '商品A専用履歴',
            'acted_at' => '2026-10-05 09:00:00',
        ]);

        // 商品Bの履歴
        StockLog::create([
            'item_id' => $itemB->id,
            'type' => 'in',
            'qty' => 20,
            'user_id' => $user->id,
            'note' => '商品B専用履歴',
            'acted_at' => '2026-10-05 10:00:00',
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('items.logs', $itemA));

        $response->assertOk();

        $response->assertSee('商品A専用履歴');
        $response->assertDontSee('商品B専用履歴');
    }

    /**
     * 入出庫履歴を訂正すると元履歴を残したまま訂正履歴が作成される
     */
    public function test_stock_log_correction_creates_correction_log_without_modifying_original(): void
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => '農業資材',
        ]);

        $item = Item::create([
            'category_id' => $category->id,
            'name' => '訂正テスト商品',
            'sku' => 'STOCK-008',
            'unit' => '個',
            'minimum_stock' => 5,
            'note' => null,
        ]);

        $originalLog = StockLog::create([
            'item_id' => $item->id,
            'type' => 'in',
            'qty' => 10,
            'user_id' => $user->id,
            'note' => '元の入庫履歴',
            'acted_at' => '2026-10-05 09:00:00',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('stock-logs.corrections.store', $originalLog), [
                'correction_reason' => '誤って登録したため',
            ]);

        $response->assertRedirect(route('items.logs',$item));

        // 元の履歴が変更されていないことを確認
        $originalLog->refresh();

        $this->assertEquals('in', $originalLog->type);
        $this->assertEquals(10, $originalLog->qty);
        $this->assertEquals('元の入庫履歴', $originalLog->note);

        // 元履歴を打ち消す訂正履歴を確認
        $correctionLog = StockLog::where(
            'corrected_log_id',
            $originalLog->id
        )->first();

        $this->assertNotNull($correctionLog);
        $this->assertEquals($item->id, $correctionLog->item_id);
        $this->assertEquals($user->id, $correctionLog->user_id);
        $this->assertEquals('out', $correctionLog->type);
        $this->assertEquals(10, $correctionLog->qty);
        $this->assertEquals(
            '誤って登録したため',
            $correctionLog->correction_reason
        );
    }
        /**
         * 同じ入出庫履歴を二重に訂正できない
         */
        public function test_stock_log_cannot_be_corrected_twice(): void
        {
            $user = User::factory()->create();

            $category = Category::create([
                'name' => '農業資材',
            ]);

            $item = Item::create([
                'category_id' => $category->id,
                'name' => '二重訂正テスト商品',
                'sku' => 'STOCK=009',
                'unit' => '個',
                'minimum_stock' => 5,
                'note' => null,
            ]);

            $originalLog = StockLog::create([
                'item_id' => $item->id,
                'type' => 'in',
                'qty' => 10,
                'user_id' => $user->id,
                'note' => '元の履歴',
                'acted_at' => '2026-10-06 08:00:00',
            ]);

            // 1回目の訂正
            $firstResponse = $this
                ->actingAs($user)
                ->post(
                    route('stock-logs.corrections.store', $originalLog),
                    [
                        'correction_reason' => '1回目の訂正',
                    ]
                );

            $firstResponse->assertRedirect(
                route('items.logs', $item)
            );

            // 訂正履歴が1件作成されたことを確認
            $this->assertEquals(
                1,
                StockLog::where(
                    'corrected_log_id',
                    $originalLog->id
                )->count()
            );

            // 同じ元履歴をもう一度訂正
            $secondResponse = $this
                ->actingAs($user)
                ->post(
                    route('stock-logs.corrections.store', $originalLog),
                    [
                        'correction_reason' => '2回目の訂正',
                    ]
                );

            $secondResponse->assertStatus(422);

            // 2回目の訂正が履歴が作成されていないことを確認
            $this->assertEquals(
                1,
                StockLog::where(
                    'corrected_log_id',
                    $originalLog->id,
                )->count()
            );
        }
}
