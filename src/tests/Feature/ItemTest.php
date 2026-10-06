<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use App\Models\Item;
USE Illuminate\Foundation\tESTING\RefreshDatabase;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログインユーザーが商品を登録できる
     */
    public function test_authenticated_user_can_create_item(): void
    {
      $user = User::factory()->Create();

      $category = Category::create([
        'name' => '農薬',
      ]);

      $response = $this
        ->actingAs($user)
        ->post(route('items.store'), [
          'category_id'=> $category->id,
          'name' => 'テスト商品',
          'sku' => 'TEST-001',
          'unit' => '個',
          'minimum_stock' => 5,
          'note' => 'Feature Test用の商品です。',
        ]);

        $response->assertRedirect(route('items.index'));

        $this->assertDatabaseHas('items', [
          'category_id' => $category->id,
          'name' => 'テスト商品',
          'sku' => 'TEST-001',
          'unit' => '個',
          'minimum_stock' => 5,
          'note' => 'Feature Test用の商品です。',
        ]);
    }

    /**
     * ログインユーザーが商品を編集できる
     */
    public function test_authenticated_user_can_update_item(): void
    {
      $user = User::factory()->create();

      $category = Category::create([
        'name' => '肥料',
      ]);

      $item = Item::create([
        'category_id' => $category->id,
        'name' => '編集前の商品',
        'sku' => 'TEST-002',
        'unit' => '個',
        'minimum_stock' => 10,
        'note' => '編集前のメモ',
      ]);

      $response = $this
        ->actingAs($user)
        ->put(route('items.update',$item), [
          
          'category_id' => $category->id,
          'name' => '編集後の商品',
          'sku' => 'TEST-002',
          'unit' => '袋',
          'minimum_stock' => 10,
          'note' => '編集後のメモ',
        ]);

      $response->assertRedirect(route('items.index'));

      $this->assertDatabaseHas('items', [
        'id' => $item->id,
        'category_id' => $category->id,
        'name' => '編集後の商品',
        'sku' => 'TEST-002',
        'unit' => '袋',
        'minimum_stock' => 10,
        'note' => '編集後のメモ',
      ]);
    }

    /**
     * ログインユーザーが商品一覧を表示できる
     */
    public function test_authenticated_user_can_view_item_list(): void
    {
      $user = User::factory()->create();

      $category = Category::create([
        'name' => '資材',
      ]);

      $item = Item::create([
        'category_id' => $category->id,
        'name' => 'テスト資材',
        'sku' => 'TEST-003',
        'unit' => '個',
        'minimum_stock' => 3,
        'note' => '一覧表示テスト',
      ]);

      $response = $this
        ->actingAs($user)
        ->get(route('items.index'));

      $response->assertOk();

      $response->assertSee('テスト資材');
      $response->assertSee('TEST-003');
    }

    /**
     * ログインユーザーが商品詳細を表示できる
     */
    public function test_authenticated_user_can_view_item_detail(): void
    {
      $user = User::factory()->create();

      $category = Category::create([
        'name' => '資材',
      ]);

      $item = Item::create([
        'category_id' => $category->id,
        'name' => '詳細確認商品',
        'sku' => 'TEST-004',
        'unit'  => '本',
        'minimum_stock' => 2,
        'note' => '商品詳細画面のテストです。',
      ]);

      $response = $this
        ->actingAs($user)
        ->get(route('items.show', $item));

      $response->assertSee('詳細確認商品');
      $response->assertSee('TEST-004');
      $response->assertSee('商品詳細画面のテストです。');
    }

    /**
     * 商品名で商品を検索できる
     */
    public function test_authenticated_user_can_search_items_by_name(): void
    {
      $user = User::factory()->create();

      $category = Category::create([
        'name' => '農業資材',
      ]);

      Item::create([
        'category_id' => $category->id,
        'name' => 'テスト肥料',
        'sku' => 'TEST-005',
        'unit' => '袋',
        'minimum_stock' => 5,
        'note' => 'null',
      ]);

      Item::create([
        'category_id' => $category->id,
        'name' => 'テスト農薬',
        'sku' => 'TEST-006',
        'unit' => '本',
        'minimum_stock' => 3,
        'note' => 'null',
      ]);

      $response = $this
        ->actingAs($user)
        ->get(route('items.index', [
          'q' => '肥料',
        ]));
        
      $response-> assertOk();
      
      $response->assertSee('テスト肥料');
      $response->assertDontSee('テスト農薬');
    }
}
