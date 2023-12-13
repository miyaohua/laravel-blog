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
        Schema::create('commens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('评论用户');
            $table->foreignId('article_id')->comment('评论文章');
            $table->string('commen_content')->comment('评论内容');
            $table->integer('parent_id')->comment('评论父id');
            $table->integer('toplevel_id')->comment('评论一级id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commens');
    }
};
