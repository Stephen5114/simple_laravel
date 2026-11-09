<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $status = in_array($request->query('status'), ['active', 'completed'], true)
            ? $request->query('status')
            : 'all';

        $tasks = Task::query()
            ->filter($status)
            ->orderBy('is_completed')
            ->orderByRaw('due_date IS NULL, due_date')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all' => Task::count(),
            'active' => Task::where('is_completed', false)->count(),
            'completed' => Task::where('is_completed', true)->count(),
        ];

        return view('tasks.index', compact('tasks', 'status', 'counts'));
    }

    public function store(Request $request): RedirectResponse
    {
        Task::create($this->validated($request));

        return redirect()->route('tasks.index')->with('status', '任务已创建。');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($this->validated($request) + [
            'is_completed' => $request->boolean('is_completed'),
        ]);

        return redirect()->route('tasks.index')->with('status', '任务已更新。');
    }

    public function toggle(Task $task): RedirectResponse
    {
        $task->update(['is_completed' => ! $task->is_completed]);

        return back()->with('status', $task->is_completed ? '任务已完成。' : '任务已恢复为进行中。');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return back()->with('status', '任务已删除。');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'due_date' => ['nullable', 'date'],
        ], [], [
            'title' => '标题',
            'description' => '描述',
            'due_date' => '截止日期',
        ]);
    }
}
