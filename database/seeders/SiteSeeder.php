<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Site::create(['name' => 'فناوری گردشگری', 'abbr' => 'fanar']);
        Site::create(['name' => 'فولاد بافت', 'abbr' => 'baft']);
        Site::create(['name' => 'بانک گردشگری', 'abbr' => 'gardeshgari']);
        Site::create(['name' => 'سایت خبری', 'abbr' => 'khabari']);
        Site::create(['name' => 'هولدینگ ماهان', 'abbr' => 'mahan']);
    }
}
