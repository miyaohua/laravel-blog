<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommenRequest;
use App\Http\Requests\UpdateCommenRequest;
use App\Models\Commen;
use Illuminate\Support\Facades\Auth;

class CommenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommenRequest $request, Commen $commen)
    {
        // 新增评论
        $commen->user_id = Auth::id();
        $commen->article_id = $request->article_id;
        $commen->commen_content = $request->commen_content;
        $commen->parent_id = $request->parent_id ?? 0;
        $commen->toplevel_id = $request->toplevel_id ?? 0;
        $commen->save();
        return $this->success('评论成功');
    }

    /**
     * Display the specified resource.
     */
    public function show(Commen $commen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommenRequest $request, Commen $commen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Commen $commen)
    {
        //

    }
}
