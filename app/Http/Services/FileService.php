<?php

namespace App\Http\Services;

class FileService
{
    public function getFileByUser($arr)
    {
        foreach ($arr as $item) {
            // 处理附件上传用户
            $item->userName = $item->user->username;
        }
        return $arr;
    }
}
