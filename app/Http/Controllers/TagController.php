<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexTagRequest;
use App\Models\Article;
use App\Models\Tag;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTagRequest $request, Tag $tag)
    {
        //
        $this->authorize('viewAny', $tag);
        return $this->success('查询成功', Tag::paginate($request->query('size')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
        $this->authorize('delete', $tag);
        $result = Article::where('tag', 'LIKE', "%{$tag->id}%")->get();
        if ($result) {
            foreach ($result as $item) {
                $tagsString = $item->tag;
                $tagsArray = explode(',', $tagsString);
                // 找到要删除的标签在数组中的位置
                $index = array_search($tag->id, $tagsArray);
                if ($index !== false) {
                    // 从数组中移除该标签
                    unset($tagsArray[$index]);
                    // 将数组转换回字符串
                    $newTagsString = implode(',', $tagsArray);
                    // 更新模型的 tag 属性
                    $item->tag = $newTagsString;
                    // 保存更改
                    $item->save();
                }
            }
        }
        $tag->delete();
        return $this->success('删除成功', $tag);
    }
}
