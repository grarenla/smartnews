<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\RequestNews;
use App\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class NewsAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = News::list();
        return view('pages.news.news', ['list' => $list]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param RequestNews $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RequestNews $request)
    {
        News::installNews($request);
        return Redirect::route('news.form');
    }


    public function createView()
    {
        $categories = Category::list();
        return view('pages.news.news-form', ['categories' => $categories]);
    }

    public function editView($id)
    {
        $news = News::getById($id);
        $categories = Category::list();
        return view('pages.news.edit-form', ['news' => $news, 'categories' => $categories]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param RequestNews $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RequestNews $request, $id)
    {
        try {
            $news = News::getById($id);
            if ($news != null) {
                News::updateNews($request, $news);
                return redirect('/news/edit/'.$news->id)->with('notification', 'Edit success.');
            } else {
                return response()->json("News doesn't exist", 404);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        return $id;
        try {
            $new = News::getById($id);
            if ($new != null) {
                $data = News::deleteNews($new);
                return response()->json(array('Delete success', 'id' => $data->$id), 201);
            } else {
                return response()->json("News doesn't exist", 404);
            }
        } catch (\Exception $exception) {
            return response()->json($exception->getMessage(), 500);
        }
    }
}
