<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexArticleRequest;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Http\Services\ArticleService;
use App\Http\Services\TagService;
use App\Models\Article;
use App\Models\Tag;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexArticleRequest $request, Article $article)
    {
        $this->authorize('viewAny', $article);
        $ArticleService = new ArticleService();
        $result = $ArticleService->articleMent(Article::with(['category', 'user'])->orderBy('id', 'DESC')->paginate($request->query('size')));
        return $this->success('查询成功', $result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request, Article $article, Tag $tag)
    {
        $this->authorize('create', $article);
        $article->fill($request->input());
        $article->user_id = Auth::id();
        $TagService = new TagService();
        $article->tag = $TagService->addTag($request->tags);
        $article->save();
        return $this->success('新增文章成功', $article->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $this->authorize('view', $article);
        $articleTag = $article->load(['category', 'user'])->toArray()['tag'];
        $tagResult = [];
        $tagsArray = explode(',', $articleTag);
        foreach ($tagsArray as $item) {
            $tag = Tag::find($item) && Tag::find($item)->tag_name; // 使用 find 方法查找标签
            if ($tag) {
                $tagResult[] = $tag;
            }
        }
        $article->tags = $tagResult;

        return $this->success('查询成功', $article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $this->authorize('update', $article);
        $article->fill($request->input());
        if ($article->tag) {
            $TagService = new TagService();
            $article->tag = $TagService->addTag($request->tags);
        }
        $article->save();
        return $this->success('更新文章成功', $article->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        //
        $this->authorize('delete', $article);
        $article->delete();
        return $this->success('删除成功', $article->toArray());
    }
}
