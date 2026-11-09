<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_redirects_to_tasks(): void
    {
        $this->get('/')->assertRedirect('/tasks');
    }

    public function test_index_shows_empty_state(): void
    {
        $this->get('/tasks')->assertOk()->assertSee('暂无任务');
    }

    public function test_can_create_task(): void
    {
        $this->post('/tasks', ['title' => '买牛奶', 'due_date' => '2026-12-01'])
            ->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', ['title' => '买牛奶', 'is_completed' => false]);
    }

    public function test_title_is_required(): void
    {
        $this->post('/tasks', ['title' => ''])->assertSessionHasErrors('title');

        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_can_toggle_task(): void
    {
        $task = Task::factory()->create();

        $this->patch("/tasks/{$task->id}/toggle")->assertRedirect();

        $this->assertTrue($task->fresh()->is_completed);
    }

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create();

        $this->put("/tasks/{$task->id}", ['title' => '新标题', 'is_completed' => '1'])
            ->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => '新标题', 'is_completed' => true]);
    }

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $this->delete("/tasks/{$task->id}")->assertRedirect();

        $this->assertModelMissing($task);
    }

    public function test_status_filter(): void
    {
        Task::factory()->create(['title' => '进行中的任务']);
        Task::factory()->completed()->create(['title' => '完成的任务']);

        $this->get('/tasks?status=completed')
            ->assertSee('完成的任务')
            ->assertDontSee('进行中的任务');
    }
}
