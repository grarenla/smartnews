<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i = 0 ; $i < 10; $i++ ){
            DB::table('news')->insert([
                'title' => Str::random(100),
                'img' => Str::random(50).'.img',
                'description' => Str::random(200),
                'source' => Str::random(100),
                'content' => Str::random(200),
                'author' => Str::random(10),
                'category_id' => rand(1,10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),

            ]);
        }
    }
}
