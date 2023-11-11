<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    //
    public function changeAvatar(Request $request){
        // 规定限制的类型
        $arrType = ['jpg','jpeg','png','gif','webp'];
        // 获取上传的文件
        $fileR = $request->file('file');
        $lastStr = $fileR->getClientOriginalExtension();
        if(!in_array($lastStr,$arrType,true)){
            return $this->error('修改失败，文件类型检测失败');
        }
       if($request->hasFile('file')){
           $path = $fileR->store('uploads/'.date('Ym'),'public');
           $result = '{mt_blog}/storage/'.$path;
           User::where('id',Auth::id())->update(['avatar'=>$result]);
           return $this->success('修改成功',["url"=>$result]);
       }
       return $this->error('修改失败');
    }
}
