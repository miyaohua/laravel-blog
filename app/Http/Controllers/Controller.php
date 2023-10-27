<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    // 成功返回
    public function success($message,$data = []): array
    {
        return [
            "code"=>200,
            "msg"=>$message,
            "data"=>$data
        ];
    }

    // 失败返回
    public function error($message,$data = []): array
    {
        return [
            "code"=>201,
            "msg"=>$message,
            "data"=>$data
        ];
    }
}
