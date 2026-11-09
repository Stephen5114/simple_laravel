# Simple Laravel 任务清单

一个基于 **Laravel 12** 的简单任务管理（Todo）应用，用来演示 Laravel 的核心用法：路由、控制器、表单验证、Eloquent 模型、数据库迁移、Blade 模板和功能测试。

## 功能

- 新建任务（标题、描述、截止日期）
- 标记完成 / 取消完成
- 编辑、删除任务
- 按「全部 / 进行中 / 已完成」筛选，并显示数量
- 逾期任务高亮提示，列表分页
- 默认使用 SQLite，无需安装数据库服务

## 环境要求

- PHP 8.2+（需启用 `pdo_sqlite`、`mbstring`、`xml` 等常用扩展）
- Composer 2

## 本地运行

```bash
git clone https://github.com/Stephen5114/simple_laravel.git
cd simple_laravel

composer install
composer run setup   # 复制 .env、生成 APP_KEY、创建 SQLite 数据库、迁移并填充示例数据

php artisan serve    # 打开 http://localhost:8000
```

如果想使用 MySQL，修改 `.env` 中的 `DB_CONNECTION=mysql` 以及对应的 `DB_HOST`、`DB_DATABASE`、`DB_USERNAME`、`DB_PASSWORD`，然后执行 `php artisan migrate --seed`。

## 运行测试

```bash
php artisan test
```

## 项目结构

| 路径 | 说明 |
| --- | --- |
| `routes/web.php` | 页面路由 |
| `app/Http/Controllers/TaskController.php` | 任务的增删改查与状态切换 |
| `app/Models/Task.php` | 任务模型（筛选作用域、逾期判断） |
| `database/migrations/` | `tasks` 表结构 |
| `database/seeders/DatabaseSeeder.php` | 示例数据 |
| `resources/views/` | Blade 模板（使用 Tailwind CDN 样式，无需前端构建） |
| `tests/` | 单元测试和功能测试 |
