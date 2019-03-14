<?php

namespace App;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Helper\ProgressBar;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'news';
    protected $dates = ['deleted_at'];

    /**
     * @return string
     */
    public static function list()
    {
        try {
            $list = News::join('categories', 'categories.id', 'news.category_id')->select('categories.id as category_id', 'categories.name as category_name', 'news.*')->orderBy('news.id', 'desc')->paginate(20);
            return $list;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * @param $newsJson
     * @return News|string
     */
    public static function installNews($newsJson)
    {
        try {
            $news = new News();
            $news->title = $newsJson['title'];
            $news->img = $newsJson['img'];
            $news->description = $newsJson['description'];
            $news->content = $newsJson['content'];
            $news->source = $newsJson['source'];
            $news->author = $newsJson['author'];
            $news->category_id = $newsJson['category_id'];
            $news->user_id = $newsJson['user_id'];
            $news->url = str_slug($newsJson['title']).'-'.round(microtime(true) * 1000); //(new \DateTime)->getTimestamp();  //Băm title để làm friendly url
            $news->created_at = Carbon::now();
            $news->updated_at = Carbon::now();
            $news->save();
            return $news;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $newsJson
     * @param $news
     * @return string
     */
    public static function updateNews($newsJson, $news)
    {
        try {
            $news->title = $newsJson['title'];
            $news->img = $newsJson['img'];
            $news->description = $newsJson['description'];
            $news->content = $newsJson['content'];
            $news->source = $newsJson['source'];
            $news->author = $newsJson['author'];
            $news->category_id = $newsJson['category_id'];
            $news->updated_at = Carbon::now();
            $news->save();
            return $news;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $id
     * @return string
     */
    public static function getById($id)
    {
        try {
            $news = News::find($id);
            return $news;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getByUrlTitle($urlTitle)
    {
        try {
            $news = News::join('categories','news.category_id','=','categories.id')->where('url',$urlTitle)->get();
            return $news;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $id
     * @return string
     */
    public static function getByCategoryId($id)
    {
        try {
            $list = News::where('category_id', $id)->paginate(10);
            return $list;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $id
     * @return string
     */
    public static function getByUserId($id)
    {
        try {
            $list = News::join('categories','news.category_id','=','categories.id')->where('user_id', $id)->select('news.*', 'categories.name as category_name')->orderBy('news.id', 'desc')->get();
            return $list;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $id
     * @return string
     */
    public static function getByCategoryUrl($url)
    {
        try {
            $category = Category::getUrlById($url);
            $list = News::where('category_id', $category->id)->paginate(10);
            return $list;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }
    /**
     * @param $new
     * @return string
     */
    public static function deleteNews($new)
    {
        try {
            $new->delete();
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }



    public static  function deletedNewsDuplicate($output){

        $countNumDelete = 0;
        $duplicateRecords = DB::table('news')
            ->select('title')
            ->selectRaw('count(`title`) as `occurrences`')
            ->groupBy('title')
            ->having('occurrences', '>', 1)
            ->get();
        echo "\n"."Waiting to delete Duplicate news..."."\n";
        $progressBar = new ProgressBar($output, 100);
        $progressBar->start();
        foreach($duplicateRecords as $record) {
            $dontDeleteThisRow  = News::where('title', $record->title)->first();
            DB::table('news')->where('title', $record->title)->where('id', '!=', $dontDeleteThisRow->id)->delete();
            $progressBar->advance(100/count($duplicateRecords));
            $countNumDelete++;
        }

        $progressBar->finish();
        if($countNumDelete > 0){

            echo "\n" ."Success delete ".$countNumDelete." record duplicate";
            echo "\n"."Done !!!";
        }else{
            echo "\n"."No record duplicate";
        }

//        DB::table('news')->
//        ALTER TABLE tmp AUTO_INCREMENT = 3;
    }

    public static  function deletedNewsNoImg(){
        DB::table('news')
            ->where('img',' ')
            ->orwhere('title',' ')
            ->orWhere('content',' ')
            ->orWhere('content','
                            ')
            ->delete();
    }
}
//insert into categories (name) values ('Thế giới'); insert into categories (name) values ('Kinh doanh'); insert into categories (name) values ('Thể thao'); insert into categories (name) values ('Sức khoẻ'); insert into categories (name) values ('Đời sống'); insert into categories (name) values ('Khoa học'); insert into categories (name) values ('Du lịch'); insert into categories (name) values ('Pháp luật'); insert into categories (name) values ('Tâm sự'); insert into categories (name) values ('Thời sự');
