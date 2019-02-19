<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Exception;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'Category';
    protected $dates = ['deleted_at'];

    public static function list()
    {
        try {
            $list = Category::all();
            return $list;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
