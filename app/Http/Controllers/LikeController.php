<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexLikeRequest;
use App\Http\Requests\StoreLikeRequest;
use App\Http\Requests\UpdateLikeRequest;
use App\Models\Like;
use App\Models\Article;

use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexLikeRequest $request, Like $like)
    {
        // 管理员查询
        $this->authorize('create', $like);
        return $this->success('查询成功', $like::orderBy('created_at', 'DESC')->paginate($request->query('size')));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLikeRequest $request, Like $like)
    {
        // 用户点赞
        $article_id = $request->article_id;
        $user_id = Auth::id();
        $isLike =  Like::where('article_id', $article_id)->where('user_id', $user_id)->first();
        if ($isLike) {
            $isLike->delete();
            // 更新文章的点赞数量
            $count = Like::where('article_id', $article_id)->count();
            Article::where('id', $article_id)->update(['like' => $count]);
            return $this->success('取消点赞');
        }
        $like->user_id = $user_id;
        $like->article_id = $article_id;
        $like->save();
        // 更新文章的点赞数量
        $count = Like::where('article_id', $article_id)->count();
        Article::where('id', $article_id)->update(['like' => $count]);
        return $this->success('点赞成功', $like->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Like $like)
    {
        // 管理员删除
        $this->authorize('delete', $like);
        $like->delete();
        return $this->success('删除成功', $like->toArray());
    }
}
