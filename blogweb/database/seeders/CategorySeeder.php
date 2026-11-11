<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Công nghệ', 'Học tập', 'Đời sống'] as $name) {
            Category::create(['name' => $name, 'slug' => Str::slug($name)]);
        }

        // "Khác" dành cho bài không thuộc tag nào ở trên.
        $tags = ['laravel', 'tin nhanh', 'mẹo hay', 'làm việc nhóm', 'Khác'];

        foreach ($tags as $name) {
            Tag::create(['name' => $name, 'slug' => Str::slug($name)]);
        }
    }
}
