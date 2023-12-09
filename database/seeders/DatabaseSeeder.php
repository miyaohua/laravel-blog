<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Article;
use App\Models\Category;
use App\Models\File;
use App\Models\Like;
use App\Models\Link;
use App\Models\Tag;
use App\Models\test;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // 创建用户
        User::factory(10)->create();
        $user = User::where('id', '1')->first();
        $user->username = 'miyaohua';
        $user->sign = '美好的一天，这是个性签名';
        $user->email = '207781983@qq.com';
        $user->save();

        $user2 = User::where('id', '2')->first();
        $user2->username = 'suntao';
        $user2->sign = '孙涛的个性签名';
        $user2->email = 'suntao@qq.com';
        $user2->save();



        Link::factory(20)->create();

        Category::factory(20)->create();

        Article::factory(100)->create();

        Tag::factory(100)->create();

        Like::factory(10)->create();


        //        File::factory(30)->create();
    }
}
