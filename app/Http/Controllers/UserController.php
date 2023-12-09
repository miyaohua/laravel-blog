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
        Validator::make($request->input(), [
            "sign" => ['required'],
            "nikename" => ['required']
        ])->validate();
        User::where('id', Auth::id())->update(['sign' => $request->sign, 'nikename' => $request->nikename]);
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
}
