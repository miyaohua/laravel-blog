<?php
namespace App\Http\Services;

class CategoryService {
    // 递归处理分类
    public function categoryToArr($array,$fid = 0){
        $result = [];
        foreach ($array as $value){
            if($value['parent'] == $fid){
                $children = $this->categoryToArr($array,$value['id']);
                if(count($children)){
                    $value['children'] = $children;
                }
                $result[] = $value;
            }
        }
        return $result;
    }
}
