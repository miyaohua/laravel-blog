<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexFileRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Services\FileService;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(IndexFileRequest $request, File $file)
    {
        // 文件列表
        $this->authorize('viewAny', $file);
        $fileService = new FileService();
        // return $this->success('查询成功', File::with('user')->orderBy('created_at', 'DESC')->paginate($request->query('size')));

        return $this->success('查询成功', $fileService->getFileByUser(File::with('user')->orderBy('created_at', 'DESC')->paginate($request->query('size'))));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFileRequest $request)
    {
        $fileService = new FileService();
        $resultArr = $fileService->uploadFile($request->file('file'), $request->hasFile('file'));
        if ($resultArr["status"]) {
            return $this->success('上传成功', ["url" => $resultArr["message"]]);
        }
        return $this->error($resultArr["message"]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        $this->authorize('delete', $file);
        // 删除文件
        $filePath = str_replace('{mt_blog}/storage/', '/public/', $file->url);
        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            $file->delete();
            return $this->success('删除成功');
        } else {
            return $this->error('删除失败');
        }
    }
}
