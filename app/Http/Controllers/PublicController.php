<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicArticleRequest;
use App\Http\Requests\PublicPopularRequest;
use App\Models\Article;
use App\Models\Category;
use App\Models\Commen;
use App\Models\Link;
use App\Models\Tag;
use App\Models\Star;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class PublicController extends Controller
{
    public function article(PublicArticleRequest $request)
    {
        return $this->success('查询成功', Article::with(['user', 'category'])->where('status', '0')->orderBy('created_at', 'DESC')->paginate($request->query('size')));
    }



    public function link()
    {
        return $this->success('查询成功', Link::orderBy('order', 'DESC')->get());
    }



    public function popular(PublicPopularRequest $request)
    {
        return $this->success('查询成功', Article::with(['user', 'category'])->where('popular', '1')->orderBy('created_at', 'DESC')->paginate($request->query('size')));
    }



    public function articleContent(Request $request)
    {
        Validator::make($request->input(), [
            "id" => ['required']
        ])->validate();
        $result = Article::with(['user', 'category'])->where('id', $request->query('id'))->first();
        $tagResult = [];
        $tagsArray = explode(',', $result->tag);
        foreach ($tagsArray as $item) {
            $tag = Tag::find($item); // 使用 find 方法查找标签
            if ($tag) {
                $tagResult[] = $tag->tag_name;
            }
        }
        $result->tags = $tagResult;

        return $this->success('查询成功', $result);
    }


    public function getCommenContent(Request $request)
    {
        Validator::make($request->input(), [
            "article_id" => ['required']
        ])->validate();
        $commenArr = Commen::where('article_id', $request->article_id)->with(['parentComment.user', 'user'])->orderBy('created_at', 'DESC')->get();
        $commenLen = count($commenArr);
        // 递归处理评论
        function commenTree($commenArr, $id)
        {
            $branch = [];
            foreach ($commenArr as $item) {
                if ($item->toplevel_id == $id) {
                    $children = commenTree($commenArr, $item->id);
                    if ($children) {
                        $item->children = $children;
                    } else {
                        $item->children = [];
                    }
                    if ($id == 0) {
                        array_push($branch, $item);
                    } else {
                        array_unshift($branch, $item);
                    }
                }
            }
            return $branch;
        }
        $result = commenTree($commenArr, 0);
        return $this->success('查询成功', ["commen" => $result, "total" => $commenLen]);
    }

    public function getCategory()
    {
        $result = Category::orderBy('created_at', 'DESC')->get();
        return $this->success('查询成功', $result);
    }


    public function getArticleByCategory(Request $request)
    {
        Validator::make($request->input(), [
            "category_id" => ['required'],
            "page" => ['required'],
            "size" => ['required'],
        ])->validate();
        // 0 为全分类
        if ($request->category_id == 0) {
            return $this->success('查询成功', Article::orderBy('created_at', 'DESC')->with(['user', 'category'])->paginate($request->size));
        } else {
            return $this->success('查询成功', Article::where('category_id', $request->category_id)->orderBy('created_at', 'DESC')->with(['user', 'category'])->paginate($request->size));
        }
    }


    public function getUserInfoByUserId(Request $request)
    {
        Validator::make($request->input(), [
            "id" => ['required'],
        ])->validate();
        $user_id = $request->id;
        $article_count = Article::where(['user_id' => $user_id, 'status' => '0'])->count();
        $like_count = Article::where(['user_id' => $user_id])->get()->sum('like');
        $star_count = Star::where('user_id', $user_id)->count();
        $result = [
            "article_count" => $article_count,
            "like_count" => $like_count,
            "star_count" => $star_count
        ];
        return $this->success('查询成功', $result);
    }
}
