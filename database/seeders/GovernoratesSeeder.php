<?php


namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class GovernoratesSeeder extends Seeder
{
    public function run()
    {
        DB::table('governorates')->insert([
            ['governorate_id' => 1, 'name' => 'دمشق'],
            ['governorate_id' => 2, 'name' => 'ريف دمشق'],
            ['governorate_id' => 3, 'name' => 'حلب'],
            ['governorate_id' => 4, 'name' => 'حمص'],
            ['governorate_id' => 5, 'name' => 'حماة'],
            ['governorate_id' => 6, 'name' => 'اللاذقية'],
            ['governorate_id' => 7, 'name' => 'طرطوس'],
            ['governorate_id' => 8, 'name' => 'درعا'],
            ['governorate_id' => 9, 'name' => 'السويداء'],
            ['governorate_id' => 10, 'name' => 'القنيطرة'],
            ['governorate_id' => 11, 'name' => 'دير الزور'],
            ['governorate_id' => 12, 'name' => 'الرقة'],
            ['governorate_id' => 13, 'name' => 'الحسكة'],
            ['governorate_id' => 14, 'name' => 'إدلب'],
        ]);
    }
}
