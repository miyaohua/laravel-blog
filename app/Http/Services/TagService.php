<?php
namespace App\Http\Services;

use App\Models\Article;
use App\Models\Tag;

class TagService {
    public function addTag($arr):string{
        $addTagArr = [];
        foreach ($arr as $item){
           if(!Tag::where('tag_name',$item)->first()){
               array_push($addTagArr,$item);
           }
        }
        // 如果标签列表没有标签 则新增
        foreach ($addTagArr as $item) {
            $newTag = new Tag();
            $newTag->tag_name = $item;
            $newTag->save();
        }

        $result = [];
        foreach ($arr as $item){
            $id = Tag::where('tag_name',$item)->first()->id;
            array_push($result,$id);
        }

        return implode(',',$result);
    }
}
