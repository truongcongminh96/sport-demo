<?php

namespace Database\Seeders;

use App\Models\Seo;
use Illuminate\Database\Seeder;

class SeoBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Seo::truncate();
        Seo::create([
            'meta_title' => 'meta_title',
            'meta_author' => 'meta_author',
            'meta_keyword' => 'meta_keyword',
            'meta_description' => 'meta_description',
            'google_analytics' => 'google_analytics'
        ]);
    }
}
