<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Task::create([
            'title' => '阅读 Laravel 官方文档',
            'description' => '重点看路由、控制器、Eloquent 和 Blade 模板。',
            'due_date' => now()->addDays(3),
        ]);

        Task::create([
            'title' => '运行 php artisan migrate',
            'description' => '创建 SQLite 数据库中的 tasks 表。',
            'is_completed' => true,
        ]);

        Task::create([
            'title' => '给项目写一个功能测试',
            'due_date' => now()->addWeek(),
        ]);
    }
}
