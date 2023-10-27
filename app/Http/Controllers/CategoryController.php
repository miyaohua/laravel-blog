<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Article;
use App\Models\Category;
use App\Http\Services\CategoryService;
use Illuminate\Support\Facades\Request;

class CategoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum')->except('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Category::all()->toArray();
        $categroyService = new CategoryService();
        // 处理树形结构数据
        $result = $categroyService->categoryToArr($data,0);
        return CategoryResource::collection($result);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request,Category $category)
    {
        //
        $this->authorize('create',$category);
        $current = Category::where('id',$request->parent)->first();
        // 判读二级分类
        if($current && $current->parent){
            return $this->error('最多只能有二级分类');
        }
        $category->fill($request->input())->save();
        return $this->success('新增成功',$category->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $this->authorize('view',$category);
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->authorize('update',$category);
        $current = Category::where('id',$request->parent)->first();
        // 判读二级分类
        if($current && $current->parent){
            return $this->error('最多只能有二级分类');
        }
        $category->fill($request->input())->save();
        return $this->success('更新成功',$category->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete',$category);
        // 分类 => 文章 关联id清空
        Article::where('category_id',$category->id)->update(['category_id'=>null]);
        // 删除一级后还应该删除下级分类
        Category::where('parent',$category->id)->delete();
        // 删除自身
        $category->delete();
        return $this->success('删除成功',$category->toArray());
    }
}
