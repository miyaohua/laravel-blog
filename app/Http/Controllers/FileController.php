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
    public function store(StoreFileRequest $request, File $file)
    {
        $fileR = $request->file('file');
        // 文件名称
        $fileName = $fileR->getClientOriginalName();
        // 规定限制的类型
        $arrType = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $lastStr = $fileR->getClientOriginalExtension();
        if (!in_array($lastStr, $arrType, true)) {
            return $this->error('上传失败，文件类型检测失败');
        }
        if ($fileR->getSize() > 5000000) {
            return $this->error('上传失败，文件超过5M');
        };
        if ($request->hasFile('file')) {
            $path = $fileR->store('uploads/' . date('Ym'), 'public');
            $result = '{mt_blog}/storage/' . $path;
            $file->user_id = Auth::id();
            $file->url = $result;
            $file->file_name = $fileName;
            $file->save();
            return $this->success('上传成功', ["url" => $result]);
        }
        return $this->error('上传失败');
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
