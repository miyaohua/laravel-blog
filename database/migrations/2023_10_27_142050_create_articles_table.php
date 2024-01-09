<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('文章标题');
            $table->longText('content')->comment('文章内容');
            $table->longText('abstract')->comment('文章摘要');
            $table->string('thumbnail')->nullable()->comment('缩略图');
            $table->integer('like')->default(0)->comment('喜欢');
            $table->integer('star')->default(0)->comment('收藏');
            $table->integer('preview')->default(0)->comment('预览');
            $table->foreignId('category_id')->nullable()->constrained(); // 分类id
            $table->foreignId('user_id')->constrained(); // 用户id
            $table->string('tag')->nullable()->comment('文章标签');
            $table->integer('status')->default(0)->comment('0显示/1隐藏');
            $table->integer('popular')->default(1)->comment('0非热门/1热门');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
