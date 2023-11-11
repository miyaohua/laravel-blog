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
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Category $category)
    {
        $this->authorize('viewAny',$category);
        $data = Category::orderBy('created_at','DESC')->get()->toArray();
        $categroyService = new CategoryService();
        // 处理树形结构数据
        $result = $categroyService->categoryToArr($data,0);
        return $this->success('获取数据成功',$result);
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
        return $this->success('获取成功',$category->toArray());
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

        $isParent = Category::where('parent',$category->id)->first();
        if($isParent){
            return $this->error('当前分类下已有子级分类，不可作为二级分类');
        }

        if($request->id === $request->parent){
            return $this->error('父级分类不能为自身');
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
        if(Category::where('parent',$category->id)->first()){
            return $this->error('当前分类下有二级分类，无法删除');
        }
        // 分类 => 文章 关联id清空
        Article::where('category_id',$category->id)->update(['category_id'=>null]);
        // 删除自身
        $category->delete();
        return $this->success('删除成功',$category->toArray());
    }
}
