<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\PasswordRule;
use App\Rules\UsernameRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    // 登录
    public function login(Request $request)
    {
        Validator::make($request->input(), [
            "username" => ['required', new UsernameRule(), Rule::exists('users')],
            "password" => ['required', new PasswordRule()]
        ], ['username' => '用户名为4-16位字母', 'password' => '密码为6-16个不含特殊符号的字符'])->validate();
        // 验证通过
        $user = User::where('username', $request->username)->first();
        if ($user->id !== 1) {
            return $this->error('无访问权限');
        }
        if ($user && Hash::check($request->password, $user->password)) {
            // 生成token
            $token = $user->createToken('auth')->plainTextToken;
            return $this->success('登录成功', ["token" => $token, 'userInfo' => $user]);
        }
        return $this->error('账号或密码错误');
    }

    // 注册
    public function registry(Request $request, User $user)
    {
        Validator::make($request->input(), [
            "username" => ['required', new UsernameRule(), Rule::unique('users')],
            "password" => ['required', new PasswordRule(), 'confirmed'],
        ])->validate();
        // 填充用户信息保存
        $user->fill($request->input());
        $user->password = Hash::make($request->password);
        $user->save();
        return $this->success('注册成功', $user->toArray());
    }
}
