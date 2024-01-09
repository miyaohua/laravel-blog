<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\PasswordRule;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Services\FileService;
use App\Models\Like;
use App\Models\Star;
use App\Models\Article;
use App\Models\Preview;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    // 修改用户头像
    public function changeUserAvatar(StoreFileRequest $request)
    {
        $fileService = new FileService();
        $resultArr = $fileService->uploadFile($request->file('file'), $request->hasFile('file'));
        if ($resultArr["status"]) {
            User::where('id', Auth::id())->update(['avatar' => $resultArr["message"]]);
            return $this->success('修改成功', ["url" => $resultArr["message"]]);
        }
        return $this->error($resultArr["message"]);
    }

    // 修改用户信息
    public function changeUserInfo(Request $request)
    {
        if ($request->sign) {
            User::where('id', Auth::id())->update(['sign' => $request->sign]);
        }
        if ($request->nikename) {
            User::where('id', Auth::id())->update(['nikename' => $request->nikename]);
        }
        if ($request->email) {
            User::where('id', Auth::id())->update(['email' => $request->email]);
        }

        // 修改成功后获取用户信息返回给前端
        $resultInfo = User::where('id', Auth::id())->first();
        return $this->success('修改成功', $resultInfo->toArray());
    }

    // 修改密码
    public function changeUserPassword(Request $request)
    {
        Validator::make($request->input(), [
            "oldpassword" => ['required', new PasswordRule()],
            "password" => ['required', new PasswordRule(), 'confirmed'],
        ], ['password' => '密码为6-16个不含特殊符号的字符'])->validate();
        if ($request->oldpassword == $request->password) {
            return $this->error('新旧密码不可相同');
        }
        if (!Hash::check($request->oldpassword, User::where('id', Auth::id())->first()->password)) {
            return $this->error('旧密码校验失败');
        }
        User::where('id', Auth::id())->update(['password' => Hash::make($request->password)]);
        return $this->success('修改密码成功');
    }

    // 查询文章是否点赞
    public function getArticleLike(Request $request)
    {
        Validator::make($request->input(), [
            "article_id" => ['required',],
        ], ['article_id' => '文章id必传'])->validate();
        $user_id = Auth::id();
        $article_id = $request->article_id;

        $isLike = Like::where('user_id', $user_id)->where('article_id', $article_id)->first();
        return $this->success('查询成功', $isLike ? $isLike->toArray() : []);
    }

    // 查询文章是否收藏
    public function getArticleStar(Request $request)
    {
        Validator::make($request->input(), [
            "article_id" => ['required',],
        ], ['article_id' => '文章id必传'])->validate();
        $user_id = Auth::id();
        $article_id = $request->article_id;

        $isStar = Star::where('user_id', $user_id)->where('article_id', $article_id)->first();
        return $this->success('查询成功', $isStar ? $isStar->toArray() : []);
    }


    public function getUserBaseInfo()
    {
        $user_id = Auth::id();
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

    // 查询用户收藏列表
    public function getCollectList(Request $request)
    {
        Validator::make($request->input(), [
            "size" => ['required'],
            "page" => ['required']
        ])->validate();
        $user_id = Auth::id();
        $list = Star::where('user_id', $user_id)
            ->with(['article' => function ($query) {
                $query->where('status', 0)
                    ->with('user', 'category');
            }])
            ->orderBy('created_at', 'DESC')
            ->paginate($request->size);

        return $this->success('查询成功', $list);
    }


    // 查询用户喜欢列表
    public function getLikeList(Request $request)
    {
        Validator::make($request->input(), [
            "size" => ['required'],
            "page" => ['required']
        ])->validate();
        $user_id = Auth::id();
        $list = Like::where('user_id', $user_id)
            ->with(['article' => function ($query) {
                $query->where('status', 0)
                    ->with('user', 'category');
            }])
            ->orderBy('created_at', 'DESC')
            ->paginate($request->size);

        return $this->success('查询成功', $list);
    }

    // 查询用户最近浏览列表
    public function getPreviewList(Request $request)
    {

        Validator::make($request->input(), [
            "size" => ['required'],
            "page" => ['required']
        ])->validate();
        $user_id = Auth::id();
        $list = Preview::where('user_id', $user_id)
            ->with(['article' => function ($query) {
                $query->where('status', 0)
                    ->with('user', 'category');
            }])
            ->orderBy('created_at', 'DESC')
            ->paginate($request->size);

        return $this->success('查询成功', $list);
    }
}
