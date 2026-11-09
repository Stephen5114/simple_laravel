<?php

namespace Tests\Unit;

use App\Models\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function test_completed_task_is_never_overdue(): void
    {
        $task = new Task(['due_date' => '2000-01-01', 'is_completed' => true]);

        $this->assertFalse($task->isOverdue());
    }

    public function test_task_without_due_date_is_not_overdue(): void
    {
        $this->assertFalse((new Task(['title' => 'x']))->isOverdue());
    }
}
