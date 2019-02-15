<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestNews;
use App\News;
use Illuminate\Http\Request;
use function PHPSTORM_META\elementType;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $list = News::list();
        if ($list !== null) {
            return response()->json(['data' => $list], 200);
        } else {
            return response()->json(['data' => $list], 404);
        }
    }

    public function listByCategory($id)
    {
        $list = News::getByCategoryId($id);
        if ($list !== null) {
            return response()->json(['data' => $list], 200);
        } else {
            return response()->json(['data' => $list], 404);
        }
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
    public function store(Request $request)
    {
        try{
            $newsJson = $request -> json()->all();
            $data = News::installNews($newsJson);
            return response()->json(array('success' => true, 'id' => $data->id), 201);
        }catch (\Exception $exception){
            return response()->json($exception -> getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $news = News::getById($id);
        if ($news !== null) {
            return response()->json(['data' => $news], 200);
        } else {
            return response()->json(['data' => $news], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function update(RequestNews $request, $id)

    {

        try{
                $new = News::getById($id);
            if($new != null) {
                $newsJson = $request->json()->all();
                $data = News::updateNews($newsJson,$new);
                return response()->json(array('success' => true, 'id' => $data->id), 201);

            }else{
                return response()->json("News doesn't exist", 404);
            }
        }
        catch (\Exception $exception) {
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
        try{
            $new = News::getById($id);
            if($new != null){
                $data = News::deleteNews($new);
                return response()->json(array('Delete success', 'id' => $data->$id), 201);
            }else{
                return response()->json("News doesn't exist", 404);
            }
        }catch (\Exception $exception){
            return response()->json($exception->getMessage(), 500);
        }
    }
}
