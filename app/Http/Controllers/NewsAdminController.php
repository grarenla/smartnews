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
        //
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
        return  Redirect::route('news.form');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function createView() {
        $categories = Category::list();
        return view('pages.news.news-form', ['categories' => $categories]);
    }

    public function editView($id) {
        $news = News::getById($id);
        $categories = Category::list();
        return view('pages.news.edit-form', ['news'=>$news,'categories' => $categories]);
    }
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
