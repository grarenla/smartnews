<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use Mpociot\Firebase\SyncsWithFirebase;

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


    public static function deletedNewsDuplicate(){

        $duplicateRecords = DB::table('news')
            ->select('title')
            ->selectRaw('count(`title`) as `occurences`')
            ->groupBy('title')
            ->having('occurences', '>', 1)
            ->get();

        foreach($duplicateRecords as $record) {
            $dontDeleteThisRow  = News::where('title', $record->title)->first();
            DB::table('news')->where('title', $record->title)->where('id', '!=', $dontDeleteThisRow->id)->delete();
        }
    }
}
