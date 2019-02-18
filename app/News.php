<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;

class News extends Model
{
    use SoftDeletes;

    protected $table = 'news';
    protected $dates = ['deleted_at'];

    public static function list()
    {
        try {
            $list = News::all();
            return $list;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public static function installNews($newsJson){
        $news = new News();
        $news->title = $newsJson['title'];
        $news->img = $newsJson['img'];
        $news->description = $newsJson['description'];
        $news->content = $newsJson['content'];
        $news->source = $newsJson['source'];
        $news->author = $newsJson['author'];
        $news->categories_id = $newsJson['categories_id'];
        $news->created_at = Carbon::now();
        $news->updated_at = Carbon::now();
        $news->save();
        return $news;
    }
    public static function updateNews($newsJson,$news)
    {
        $news->title = $newsJson['title'];
        $news->img = $newsJson['img'];
        $news->description = $newsJson['description'];
        $news->content = $newsJson['content'];
        $news->source = $newsJson['source'];
        $news->author = $newsJson['author'];
        $news->categories_id = $newsJson['categories_id'];
        $news->created_at = Carbon::now();
        $news->updated_at = Carbon::now();
        $news->save();
        return $news;
    }

    public static function getById($id)
    {
        try {
            $news = News::find($id);
            return $news;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public static function getByCategoryId($id)
    {
        try {
            $list = News::where('categories_id', $id)->get();
            return $list;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $new
     */
    public static function deleteNews($new)
    {
        $new -> delete();
    }
}
