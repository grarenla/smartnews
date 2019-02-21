<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestNews;
use App\News;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(RequestNews $request)
    {

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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
