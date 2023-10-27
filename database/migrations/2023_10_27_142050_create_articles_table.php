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
            $table->text('content')->comment('文章内容');
            $table->foreignId('category_id')->nullable()->constrained(); // 分类id
            $table->foreignId('user_id')->constrained(); // 用户id
            $table->string('tag')->nullable()->comment('文章标签');
            $table->integer('status')->default(0)->comment('0显示/1隐藏');
            $table->integer('popular')->default(0)->comment('0非置顶/1置顶');
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
