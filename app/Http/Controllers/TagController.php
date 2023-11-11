<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTagRequest;
use App\Models\Tag;

class TagController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTagRequest $request,Tag $tag)
    {
        //
        $this->authorize('viewAny',$tag);
        return $this->success('查询成功',Tag::paginate($request->query('size')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
        $this->authorize('delete',$tag);
        $tag->delete();
        return $this->error('删除成功',$tag);
    }
}
