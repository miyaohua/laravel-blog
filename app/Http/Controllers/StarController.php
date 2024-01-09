<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexStarRequest;
use App\Http\Requests\StoreStarRequest;
use App\Http\Requests\UpdateStarRequest;
use App\Models\Star;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class StarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexStarRequest $request, Star $star)
    {
        //
        $this->authorize('create', $star);
        return $this->success('查询成功', $star::orderBy('created_at', 'DESC')->paginate($request->query('size')));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStarRequest $request, Star $star)
    {
        //
        // 用户点赞
        $article_id = $request->article_id;
        $user_id = Auth::id();
        $isStar =  Star::where('article_id', $article_id)->where('user_id', $user_id)->first();
        if ($isStar) {
            $isStar->delete();
            // 更新文章的收藏数量
            $count = Star::where('article_id', $article_id)->count();
            Article::where('id', $article_id)->update(['star' => $count]);
            return $this->success('取消收藏');
        }
        $star->user_id = $user_id;
        $star->article_id = $article_id;
        $star->save();
        // 更新文章的收藏数量
        $count = star::where('article_id', $article_id)->count();
        Article::where('id', $article_id)->update(['star' => $count]);
        return $this->success('收藏成功', $star->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Star $star)
    {
        //
        // 管理员删除
        $this->authorize('delete', $star);
        $star->delete();
        return $this->success('删除成功', $star->toArray());
    }
}
