<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublicArticleRequest;
use App\Http\Requests\PublicPopularRequest;
use App\Models\Article;
use App\Models\Link;
use App\Models\Test;
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
        return  $this->success('查询成功', $result);
    }
}
