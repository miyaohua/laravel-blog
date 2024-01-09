<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexPreviewRequest;
use App\Http\Requests\StorePreviewRequest;
use App\Http\Requests\UpdatePreviewRequest;
use App\Models\Preview;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class PreviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexPreviewRequest $request, Preview $preview)
    {
        //
        $this->authorize('create', $preview);
        return $this->success('查询成功', $preview::orderBy('created_at', 'DESC')->paginate($request->query('size')));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePreviewRequest $request, Preview $preview)
    {
        //
        $article_id = $request->article_id;
        $user_id = Auth::id();
        if ($user_id) {
            $isPreview = Preview::where('article_id', $article_id)->where('user_id', $user_id)->first();
            if ($isPreview) {
                $isPreview->delete();
            }
            $preview->user_id = $user_id;
            $preview->article_id = $article_id;
            $preview->save();
            // 更新文章的预览数量
            Article::where('id', $article_id)->increment('preview', 1);
            return $this->success('更新预览记录');
        } else {
            // 更新文章的预览数量
            Article::where('id', $article_id)->increment('preview', 1);
            return $this->success('更新预览记录');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preview $preview)
    {
        // 管理员删除
        $this->authorize('delete', $preview);
        $preview->delete();
        return $this->success('删除成功', $preview->toArray());
    }
}
