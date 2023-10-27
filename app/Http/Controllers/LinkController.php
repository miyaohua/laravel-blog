<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLinkRequest;
use App\Http\Requests\UpdateLinkRequest;
use App\Http\Resources\LinkResource;
use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function __construct(){
        $this->middleware('auth:sanctum')->except('index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 所有友情连接
        return LinkResource::collection(Link::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLinkRequest $request,Link $link)
    {
        $this->authorize('create',$link);
        // 新建友情连接
        $link->fill($request->input())->save();
        return $this->success('新增成功',$link->toArray());
    }

    /**
     * Display the specified resource.
     */
    public function show(Link $link)
    {
        $this->authorize('view',$link);
        // 单个友情链接信息
        return new LinkResource($link);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLinkRequest $request, Link $link)
    {
        $this->authorize('update',$link);
        $link->fill($request->input())->save();
        return $this->success('更新成功',$link->toArray());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        $this->authorize('delete',$link);
        $link->delete();
        return $this->success('删除成功',$link->toArray());
    }
}
