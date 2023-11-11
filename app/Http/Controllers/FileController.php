<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request,File $file)
    {
        $fileR = $request->file('file');
        // 文件名称
        $fileName = $fileR->getClientOriginalName();
        // 规定限制的类型
        $arrType = ['jpg','jpeg','png','gif','webp'];
        $lastStr = $fileR->getClientOriginalExtension();
        if(!in_array($lastStr,$arrType,true)){
            return $this->error('上传失败，文件类型检测失败');
        }
        if($fileR->getSize() > 5000000){
            return $this->error('上传失败，文件超过5M');
        };
        if($request->hasFile('file')){
            $path = $fileR->store('uploads/'.date('Ym'),'public');
            $result = '{mt_blog}/storage/'.$path;
            $file->user_id = Auth::id();
            $file->url = $result;
            $file->file_name = $fileName;
            $file->save();
            return $this->success('上传成功',["url"=>$result]);
        }
        return $this->error('上传失败');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        // 删除文件
        $filePath = str_replace('{mt_blog}','',$file->url);
        $file->delete();
        Storage::delete($filePath);
        return $this->success('删除成功');
    }
}
