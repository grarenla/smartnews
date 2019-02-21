<?php

use Carbon\Carbon;
use Illuminate\Support\Str;
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
        $arrName = ['Xã hội', 'Thể thao', 'Thế giới',
                    'Kinh doang', 'Sức khoẻ', 'Đời sống',
                    'Khoa học', 'Du lịch', 'Pháp luật',
                    'Tâm sự', 'Thời sự'];
       for($i = 0 ; $i < count($arrName); $i++ ){
           DB::table('category')->insert([
                'name' => $arrName[$i],
               'created_at' => Carbon::now(),
               'updated_at' => Carbon::now(),

           ]);
       }
    }
}
