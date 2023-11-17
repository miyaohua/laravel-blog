<?php

namespace App\Http\Services;

use App\Models\File;
use Illuminate\Support\Facades\Auth;

class FileService
{
    public function uploadFile($fileR, $isUpload)
    {
        $fileName = $fileR->getClientOriginalName();
        // 规定限制的类型
        $arrType = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $lastStr = $fileR->getClientOriginalExtension();
        if (!in_array($lastStr, $arrType, true)) {
            return [false, '上传失败，文件类型检测失败'];
        }
        if ($fileR->getSize() > 5000000) {
            return ["status" => false, "message" => '上传失败，文件超过5M'];
        };
        if ($isUpload) {
            $file = new File();
            $path = $fileR->store('uploads/' . date('Ym'), 'public');
            $result = '{mt_blog}/storage/' . $path;
            $file->user_id = Auth::id();
            $file->url = $result;
            $file->file_name = $fileName;
            $file->save();
            return ["status" => true, "message" => $result];
        }
        return ["status" => false, "message" => '上传失败'];
    }

    public function getFileByUser($arr)
    {
        foreach ($arr as $item) {
            // 处理附件上传用户
            $item->userName = $item->user->username;
        }
        return $arr;
    }
}
