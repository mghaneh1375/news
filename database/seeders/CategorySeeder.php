<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        NewsCategory::create([
            'id' => 1,
            'name' => 'گردشگری',
            'nameEn' => 'Tourism',
            'icon' => 'torism.svg',
            'top' => true,
            'parentId' => 0
        ]);

        NewsCategory::create([
            'id' => 2,
            'name' => 'صنایع دستی',
            'nameEn' => 'Handicrafts',
            'icon' => 'handicrafts.svg',
            'top' => true,
            'parentId' => 0
        ]);

        NewsCategory::create([
            'id' => 3,
            'name' => 'اقتصادی',
            'nameEn' => 'Economy',
            'icon' => 'economy.svg',
            'top' => true,
            'parentId' => 0
        ]);

        NewsCategory::create([
            'id' => 4,
            'name' => 'میراث فرهنگی',
            'nameEn' => 'Heritage',
            'icon' => 'Heritage.svg',
            'top' => true,
            'parentId' => 0
        ]);

        NewsCategory::create([
            'id' => 5,
            'name' => 'اکوسیستم استارتاپی',
            'nameEn' => 'Ecosystem',
            'icon' => 'ecosystem.svg',
            'top' => false,
            'parentId' => 0
        ]);

        NewsCategory::create([
            'id' => 6,
            'name' => 'استان ها',
            'nameEn' => 'States',
            'icon' => 'states.svg',
            'top' => false,
            'parentId' => 0
        ]);

        NewsCategory::create([
            'id' => 7,
            'name' => 'ویدیو پیشنهادی',
            'nameEn' => 'Suggested videos',
            'top' => false,
            'parentId' => 0
        ]);

        NewsCategory::create([
            'id' => 8,
            'name' => 'ویدیو برگزیده',
            'nameEn' => 'Top videos',
            'top' => false,
            'parentId' => 0
        ]);

        NewsCategory::create([
            'id' => 9,
            'name' => 'گالری تصاویر',
            'nameEn' => 'Gallery',
            'top' => false,
            'parentId' => 0
        ]);
    }
}
