<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ServiceCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    DB::table('service_categories')->insert([
        ['category_id' => 1, 'name' => 'كهرباء'],
        ['category_id' => 2, 'name' => 'سباكة'],
        ['category_id' => 3, 'name' => 'نجارة'],
        ['category_id' => 4, 'name' => 'حدادة'],
        ['category_id' => 5, 'name' => 'دهان'],
        ['category_id' => 6, 'name' => 'تبريد وتكييف'],
        ['category_id' => 7, 'name' => 'تنظيف'],
        ['category_id' => 8, 'name' => 'نقل أثاث'],
        ['category_id' => 9, 'name' => 'تركيب كاميرات'],
        ['category_id' => 10, 'name' => 'تركيب إنترنت'],
        ['category_id' => 12, 'name' => 'تنسيق حدائق'],
        ['category_id' => 13, 'name' => 'تعقيم'],
        ['category_id' => 14, 'name' => 'نقل عام'],
        ['category_id' => 15, 'name' => 'غسيل سيارات'],
        ['category_id' => 16, 'name' => 'تركيب زجاج'],
    ]);
}
}
