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

        $demoEmail = config('app.demo_user_email');
        $demoPassword = config('app.demo_user_password');

        $user = DB::table('users')
            ->where('email', $demoEmail)
            ->first();

        if ($user) {
            $userId = $user->id;

            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'name' => 'デモユーザー',
                    'password' => Hash::make($demoPassword),
                    'email_verified_at' => now(),
                    'updated_at' => now(),
                ]);
        } else {
            $userId = DB::table('users')->insertGetId([
                'name' => 'デモユーザー',
                'email' => $demoEmail,
                'password' => Hash::make($demoPassword),
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
        */

        $items = [
            // -------------------------------------------------
            // 肥料
            // -------------------------------------------------
            [
                'name' => '化成肥料14-14-14',
                'sku' => 'FER-001',
                'unit' => '袋',
                'category' => '肥料',
                'target_qty' => 25,
                'minimum_stock' => 10,
                'note' => '元肥・追肥用',
            ],
            [
                'name' => '有機配合肥料',
                'sku' => 'FER-002',
                'unit' => '袋',
                'category' => '肥料',
                'target_qty' => 8,
                'minimum_stock' => 10,
                'note' => '野菜全般に使用',
            ],
            [
                'name' => '苦土石灰',
                'sku' => 'FER-003',
                'unit' => '袋',
                'category' => '肥料',
                'target_qty' => 3,
                'minimum_stock' => 5,
                'note' => '土壌改良用',
            ],
            [
                'name' => '液体肥料A',
                'sku' => 'FER-004',
                'unit' => '本',
                'category' => '肥料',
                'target_qty' => 0,
                'minimum_stock' => 5,
                'note' => null,
            ],

            // -------------------------------------------------
            // 農薬
            // -------------------------------------------------
            [
                'name' => '殺菌剤A',
                'sku' => 'PES-001',
                'unit' => '本',
                'category' => '農薬',
                'target_qty' => 12,
                'minimum_stock' => 5,
                'note' => '病害対策用',
            ],
            [
                'name' => '殺菌剤B',
                'sku' => 'PES-002',
                'unit' => '袋',
                'category' => '農薬',
                'target_qty' => 2,
                'minimum_stock' => 3,
                'note' => null,
            ],
            [
                'name' => '殺虫剤A',
                'sku' => 'PES-003',
                'unit' => '本',
                'category' => '農薬',
                'target_qty' => 0,
                'minimum_stock' => 2,
                'note' => '害虫発生時に使用',
            ],
            [
                'name' => '展着剤',
                'sku' => 'PES-004',
                'unit' => '本',
                'category' => '農薬',
                'target_qty' => 8,
                'minimum_stock' => 0,
                'note' => '最低在庫数未設定のサンプル',
            ],

            // -------------------------------------------------
            // 種苗・育苗用品
            // -------------------------------------------------
            [
                'name' => '72穴セルトレイ',
                'sku' => 'SED-001',
                'unit' => '枚',
                'category' => '種苗',
                'target_qty' => 120,
                'minimum_stock' => 50,
                'note' => '育苗用',
            ],
            [
                'name' => '128穴セルトレイ',
                'sku' => 'SED-002',
                'unit' => '枚',
                'category' => '種苗',
                'target_qty' => 35,
                'minimum_stock' => 50,
                'note' => null,
            ],
            [
                'name' => '育苗ポット9cm',
                'sku' => 'SED-003',
                'unit' => '個',
                'category' => '種苗',
                'target_qty' => 500,
                'minimum_stock' => 200,
                'note' => '鉢上げ用',
            ],
            [
                'name' => '育苗培土',
                'sku' => 'SED-004',
                'unit' => '袋',
                'category' => '種苗',
                'target_qty' => 0,
                'minimum_stock' => 10,
                'note' => '播種・育苗用',
            ],

            // -------------------------------------------------
            // 資材
            // -------------------------------------------------
            [
                'name' => '園芸支柱120cm',
                'sku' => 'MAT-001',
                'unit' => '本',
                'category' => '資材',
                'target_qty' => 200,
                'minimum_stock' => 50,
                'note' => null,
            ],
            [
                'name' => '園芸支柱180cm',
                'sku' => 'MAT-002',
                'unit' => '本',
                'category' => '資材',
                'target_qty' => 95,
                'minimum_stock' => 50,
                'note' => null,
            ],
            [
                'name' => '誘引ひも',
                'sku' => 'MAT-003',
                'unit' => '巻',
                'category' => '資材',
                'target_qty' => 5,
                'minimum_stock' => 10,
                'note' => '誘引作業用',
            ],
            [
                'name' => '防虫ネット',
                'sku' => 'MAT-004',
                'unit' => '枚',
                'category' => '資材',
                'target_qty' => 14,
                'minimum_stock' => 5,
                'note' => null,
            ],
            [
                'name' => '農業用マルチ',
                'sku' => 'MAT-005',
                'unit' => '巻',
                'category' => '資材',
                'target_qty' => 7,
                'minimum_stock' => 5,
                'note' => '黒マルチ',
            ],
            [
                'name' => '収穫用コンテナ',
                'sku' => 'MAT-006',
                'unit' => '個',
                'category' => '資材',
                'target_qty' => 60,
                'minimum_stock' => 20,
                'note' => '収穫・運搬用',
            ],

            // -------------------------------------------------
            // その他
            // -------------------------------------------------
            [
                'name' => '作業用手袋M',
                'sku' => 'OTH-001',
                'unit' => '双',
                'category' => 'その他',
                'target_qty' => 30,
                'minimum_stock' => 10,
                'note' => '消耗品',
            ],
            [
                'name' => '計量カップ',
                'sku' => 'OTH-002',
                'unit' => '個',
                'category' => 'その他',
                'target_qty' => 6,
                'minimum_stock' => 0,
                'note' => '最低在庫数未設定のサンプル',
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
                    'minimum_stock' => $itemData['minimum_stock'],
                    'note' => $itemData['note'],
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
            * 最終的な現在庫は変わらない
            */
        $this->createTodayStockLogs(
            $createdItemIds,
            $userId
        );

        $this->createCorrectionDemoLogs($userId);
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

    /**
     * 訂正機能確認用の履歴を作成する
     */
    private function createCorrectionDemoLogs(int $userId): void
    {
        $correctionSamples = [
            [
                'sku' => 'FER-001',
                'type' => 'out',
                'qty' => '5',
                'reason' => '出庫数量を誤って入力したため',
                'days_ago' => 4,
            ],
            [
                'sku' => 'SED-003',
                'type' => 'in',
                'qty' => '50',
                'reason' => '別の商品を誤って入庫登録したため',
                'days_ago' => 2,
            ],
        ];

        foreach ($correctionSamples as $sample) {
            $item = DB::table('items')
                ->where('sku', $sample['sku'])
                ->first();

            if (!$item) {
                continue;
            }

            $originalDate = now()
                ->subDays($sample['days_ago'])
                ->setTime(10, 0);

            /**
             * 誤って登録された元履歴
             */
            $originalLogId = DB::table('stock_logs')
                ->insertGetId([
                    'corrected_log_id' => null,
                    'item_id' => $item->id,
                    'user_id' => $userId,
                    'type' => $sample['type'],
                    'qty' => $sample['qty'],
                    'note' => '訂正機能確認用データ',
                    'correction_reason' => null,
                    'acted_at' => $originalDate,
                    'created_at' => $originalDate,
                    'updated_at' => $originalDate,
                ]);

            /**
             * 元履歴を打ち消す訂正履歴
             */
            $correctionType = $sample['type'] === 'in'
                ? 'out'
                : 'in';

            $correctionDate = $originalDate
                ->copy()
                ->addMinutes(30);

            DB::table('stock_logs')->insert([
                'corrected_log_id' => $originalLogId,
                'item_id' => $item->id,
                'user_id' => $userId,
                'type' => $correctionType,
                'qty' => $sample['qty'],
                'note' => null,
                'correction_reason' => $sample['reason'],
                'acted_at' => $correctionDate,
                'created_at' => $originalDate,
                'updated_at' => $originalDate,
            ]);
        }
    }
}
