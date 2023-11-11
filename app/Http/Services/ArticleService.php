<?php
namespace App\Http\Services;
class ArticleService {
    public function articleMent($arr){
        foreach ($arr as $item ){
            $item['categoryName'] = $item->category->name ?? null;
            $item['userName'] = $item->user->username ?? null;
        }
        return $arr;
    }
}
