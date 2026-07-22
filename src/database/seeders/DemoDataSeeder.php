<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * デモデータを登録する
     */
    public function run(): void
    {
        /*
        |-------------------------------------------------
        | デモユーザー
        |-------------------------------------------------
        */

        $user = DB::table('users')
            ->where('email', 'demo@example.com')
            ->first();

        if ($user) {
            $userId = $user->id;
        } else {
            $userId = DB::table('users')->insertGetId([
                'name' =>'デモユーザー',
                'email' => 'demo@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /*
        |-------------------------------------------------
        | カテゴリーIDを取得
        |-------------------------------------------------
        |
        |カテゴリーの登録はCategoryに任せます。
        |
        |CategorySeederで登録されているカテゴリー
        |   ・肥料
        |   ・農薬
        |   ・資材
        |   ・種苗
        |   ・その他
        |
        */

        $categoryIds = DB::table('categories')
            ->pluck('id', 'name')
            ->toArray();

        /*
        |-------------------------------------------------
        | 商品データ
        |-------------------------------------------------
        |
        | target_qtyは、入出庫履歴を集計した後の現在個です。
        |
        */

        $items = [
            [
                'name' => '化成肥料14-14-14',
                'sku' => 'FER-001',
                'unit' => '袋',
                'category' => '肥料',
                'target_qty' => 25,
            ],
            [
                'name' => '有機配合肥料',
                'sku' => 'FER-002',
                'unit' => '袋',
                'category' => '肥料',
                'target_qty' => 8,
            ],
            [
                'name' => '苦土石灰',
                'sku' => 'FER-003',
                'unit' => '袋',
                'category' => '肥料',
                'target_qty' => 3,
            ],
            [
                'name' => '粒状ようりん',
                'sku' => 'FER-004',
                'unit' => '袋',
                'category' => '肥料',
                'target_qty' => 0,
            ],
            [
                'name' => '液体肥料A',
                'sku' => 'FER-005',
                'unit' => '本',
                'category' => '肥料',
                'target_qty' => 42,
            ],
            [
                'name' => '殺菌剤A',
                'sku' => 'PES-001',
                'unit' => '本',
                'category' => '農薬',
                'target_qty' => 2,
            ],
            [
                'name' => '殺菌剤B',
                'sku' => 'PES-002',
                'unit' => '袋',
                'category' => '農薬',
                'target_qty' => 12,
            ],
            [
                'name' => '殺虫剤A',
                'sku' => 'PES-003',
                'unit' => '本',
                'category' => '農薬',
                'target_qty' => 1,
            ],
            [
                'name' => '殺虫剤B',
                'sku' => 'PES-004',
                'unit' => '袋',
                'category' => '農薬',
                'target_qty' => 18,
            ],
            [
                'name' => '展着剤',
                'sku' => 'PES-005',
                'unit' => '本',
                'category' => '農薬',
                'target_qty' => 0,
            ],
            [
                'name' => '72穴セルトレイ',
                'sku' => 'SED-001',
                'unit' => '枚',
                'category' => '種苗',
                'target_qty' => 120,
            ],
            [
                'name' => '128穴セルトレイ',
                'sku' => 'SED-002',
                'unit' => '枚',
                'category' => '種苗',
                'target_qty' => 85,
            ],
            [
                'name' => '育苗ポット9cm',
                'sku' => 'SED-003',
                'unit' => '個',
                'category' => '種苗',
                'target_qty' => 500,
            ],
            [
                'name' => '育苗培土',
                'sku' => 'SED-004',
                'unit' => '袋',
                'category' => '種苗',
                'target_qty' => 4,
            ],
            [
                'name' => '育苗箱',
                'sku' => 'SED-005',
                'unit' => '枚',
                'category' => '種苗',
                'target_qty' => 35,
            ],
            [
                'name' => '園芸支柱120cm',
                'sku' => 'MAT-001',
                'unit' => '本',
                'category' => '資材',
                'target_qty' => 200,
            ],
            [
                'name' => '園芸支柱180cm',
                'sku' => 'MAT-002',
                'unit' => '本',
                'category' => '資材',
                'target_qty' => 95,
            ],
            [
                'name' => '誘引ひも',
                'sku' => 'MAT-003',
                'unit' => '巻',
                'category' => '資材',
                'target_qty' => 5,
            ],
            [
                'name' => '防虫ネット',
                'sku' => 'MAT-004',
                'unit' => '枚',
                'category' => '資材',
                'target_qty' => 14,
            ],
            [
                'name' => '農業用マルチ',
                'sku' => 'MAT-005',
                'unit' => '巻',
                'category' => '資材',
                'target_qty' => 7,
            ],
            [
                'name' => '収穫用コンテナ',
                'sku' => 'MAT-006',
                'unit' => '個',
                'category' => '資材',
                'target_qty' => 60,
            ],
            [
                'name' => '剪定ばさみ',
                'sku' => 'MAT-007',
                'unit' => '丁',
                'category' => '資材',
                'target_qty' => 3,
            ],
            [
                'name' => '作業用手袋M',
                'sku' => 'OTH-001',
                'unit' => '双',
                'category' => 'その他',
                'target_qty' => 30,
            ],
            [
                'name' => '作業用手袋L',
                'sku' => 'OTH-002',
                'unit' => '双',
                'category' => 'その他',
                'target_qty' => 22,
            ],
            [
                'name' => '計量カップ',
                'sku' => 'OTH-003',
                'unit' => '個',
                'category' => 'その他',
                'target_qty' => 6,
            ],
            [
                'name' => '農薬用マスク',
                'sku' => 'OTH-004',
                'unit' => '枚',
                'category' => 'その他',
                'target_qty' => 4,
            ],
            [
                'name' => '収穫用かご',
                'sku' => 'OTH-005',
                'unit' => '個',
                'category' => 'その他',
                'target_qty' => 16,
            ],
            [
                'name' => 'ラベルシール',
                'sku' => 'OTH-006',
                'unit' => '冊',
                'category' => 'その他',
                'target_qty' => 0,
            ],
            [
                'name' => '油性マーカー',
                'sku' => 'OTH-007',
                'unit' => '本',
                'category' => 'その他',
                'target_qty' => 9,
            ],
            [
                'name' => '記録用ノート',
                'sku' => 'OTH-008',
                'unit' => '冊',
                'category' => 'その他',
                'target_qty' => 15,
            ],
        ];

        $createdItemIds = [];

        foreach ($items as $index => $itemData) {
            /*
            * カテゴリーが存在しない場合は処理を中断する
            */
            if (!isset($categoryIds[$itemData['category']])) {
                throw new \RuntimeException(
                    "カテゴリー 「{$itemData['category']}」が登録されていません。"
                );
            }

            /*
            * 商品ごとに登録日をずらす
            * 登録日順の並び替えテストに使用する
            */
            $itemCreatedAt = Carbon::now()
                ->subDays(count($items) - $index + 7);

            /*
            *同じSKUの商品がある場合は再登録しない
            */
            $existingItem = DB::table('items')
                ->where('sku', $itemData['sku'])
                ->first();
            
                if ($existingItem) {
                    $itemId = $existingItem->id;
                } else {
                    $itemId = DB::table('items')->insertGetId([
                        'name' => $itemData['name'],
                        'sku' => $itemData['sku'],
                        'unit' => $itemData['unit'],
                        'category_id' => $categoryIds[$itemData['category']],
                        'created_at' => $itemCreatedAt,
                        'updated_at' => $itemCreatedAt,
                    ]);

                    $this->createStockLogs(
                        $itemId,
                        $userId,
                        $itemData['target_qty'],
                        $itemCreatedAt
                    );
                }

                $createdItemIds[] = $itemId;
            }

            /*
            * ダッシュボードの「本日の入庫・出庫件数」を 
            * 確認できるように、本日の履歴も作成する　
            *  
            * 入庫と出庫を同数にしているため、
            * 最終的な現在地は変わらない
            */
            $this->createTodayStockLogs(
                $createdItemIds,
                $userId
            );
        }

        /**
         * 指定した現在庫になるように入出庫履歴を作成する
        */
        private function createStockLogs(
            int $itemId,
            int $userId,
            int $targetQty,
            Carbon $baseDate
        ): void {
            $firstOutQty = ($itemId % 5) + 3;
            $secondOutQty = ($itemId % 4) + 1;

            $totalOutQty = $firstOutQty + $secondOutQty;
            $initialInQty = $targetQty + $totalOutQty;

            $firstInDate = $baseDate->copy()->addDay();
            $firstOutDate = $baseDate->copy()->addDays(3);
            $secondOutDate = $baseDate->copy()->addDays(5);

            DB::table('stock_logs')->insert([
                [
                    'item_id' => $itemId,
                    'user_id' => $userId,
                    'type' => 'in',
                    'qty' => $initialInQty,
                    'acted_at' => $firstInDate,
                    'created_at' => $firstInDate,
                    'updated_at' => $firstInDate,
                ],
                [
                    'item_id' => $itemId,
                    'user_id' => $userId,
                    'type' => 'out',
                    'qty' => $firstOutQty,
                    'acted_at' => $firstOutDate,
                    'created_at' => $firstOutDate,
                    'updated_at' => $firstOutDate,
                ],
                [
                    'item_id' => $itemId,
                    'user_id' => $userId,
                    'type' => 'out',
                    'qty' => $secondOutQty,
                    'acted_at' => $secondOutDate,
                    'created_at' => $secondOutDate,
                    'updated_at' => $secondOutDate,
                ],
            ]);
        }

        /*
        * ダッシュボード確認用の本日の入出庫履歴を作成する
        */
        private function createTodayStockLogs(
            array $itemIds,
            int $userId
        ): void {
            $todayDemoExists = DB::table('stock_logs')
                ->whereDate('acted_at', today())
                ->whereIn('item_id', array_slice($itemIds, 0, 3))
                ->exists();

            if ($todayDemoExists) {
                return;
            }

            foreach (array_slice($itemIds, 0, 3) as $index => $itemId) {
                $qty = $index + 1;
                $actedAt = now();

                DB::table('stock_logs')->insert([
                    [
                        'item_id' => $itemId,
                        'user_id' => $userId,
                        'type' => 'in',
                        'qty' => $qty,
                        'acted_at' => $actedAt,
                        'created_at' => $actedAt,
                        'updated_at' => $actedAt, 
                    ],
                    [
                        'item_id' => $itemId,
                        'user_id' => $userId,
                        'type' => 'out',
                        'qty' => $qty,
                        'acted_at' => $actedAt,
                        'created_at' => $actedAt,
                        'updated_at' => $actedAt,
                    ],
                ]);
            }
        }
    }


