<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // 一件だけinsertする
        DB::table('categories')->insert([
            'category' => '家具',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        # paramに配列を代入
        $param = [
            [
                'category' => '家電',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
        ];
        # DB::table->insertでレコードの登録
        DB::table('categories')->insert($param);
    }
}
