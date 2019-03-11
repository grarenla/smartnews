<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;
use phpDocumentor\Reflection\Types\Null_;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'categories';
    protected $dates = ['deleted_at'];

    public static function list()
    {
        try {
            $list = Category::all();
            return $list;
        } catch (Exception $e) {
            return null;
        }
    }
    public static function getUrlById($url){
        try{
            $category = Category::where('category_url', $url)->get();
            return $category[0];
        }catch (Exception $e) {
            return null;
        }
    }
}
