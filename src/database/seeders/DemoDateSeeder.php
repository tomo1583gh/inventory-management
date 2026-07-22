<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoDateSeeder extends Seeder
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
                'password' => Hash::male('password'),
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
        |   ・育苗
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
        | target_qtyは、入出庫履歴を集計した語の現在個です。
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
        }
    }
}
