@extends('layouts.app')

@section('title', '任务清单')

@section('content')
    <section class="mb-8 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h1 class="mb-4 text-xl font-semibold">新建任务</h1>
        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-3">
            @csrf
            <input type="text" name="title" value="{{ old('title') }}" required maxlength="255"
                   placeholder="要做什么？"
                   class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <textarea name="description" rows="2" maxlength="2000" placeholder="补充说明（可选）"
                      class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('description') }}</textarea>
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    截止日期
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           class="rounded-lg border border-slate-300 px-3 py-1.5">
                </label>
                <button type="submit"
                        class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700">
                    添加任务
                </button>
            </div>
        </form>
    </section>

    <nav class="mb-4 flex gap-2 text-sm">
        @foreach (['all' => '全部', 'active' => '进行中', 'completed' => '已完成'] as $key => $label)
            <a href="{{ route('tasks.index', $key === 'all' ? [] : ['status' => $key]) }}"
               @class([
                   'rounded-full px-3 py-1 border',
                   'bg-slate-900 text-white border-slate-900' => $status === $key,
                   'bg-white text-slate-600 border-slate-300 hover:bg-slate-100' => $status !== $key,
               ])>
                {{ $label }} <span class="opacity-70">{{ $counts[$key] }}</span>
            </a>
        @endforeach
    </nav>

    @if ($tasks->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-300 bg-white px-6 py-12 text-center text-slate-500">
            @if ($status === 'completed')
                还没有已完成的任务。
            @elseif ($status === 'active')
                没有进行中的任务，休息一下吧。
            @else
                暂无任务，在上方添加第一个任务。
            @endif
        </div>
    @else
        <ul class="divide-y divide-slate-200 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @foreach ($tasks as $task)
                <li class="flex items-start gap-3 px-4 py-4">
                    <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" title="{{ $task->is_completed ? '标记为未完成' : '标记为已完成' }}"
                                @class([
                                    'mt-0.5 flex h-5 w-5 items-center justify-center rounded border text-xs',
                                    'border-emerald-500 bg-emerald-500 text-white' => $task->is_completed,
                                    'border-slate-400 hover:border-indigo-500' => ! $task->is_completed,
                                ])>
                            @if ($task->is_completed) &#10003; @endif
                        </button>
                    </form>

                    <div class="min-w-0 flex-1">
                        <p @class(['font-medium break-words', 'line-through text-slate-400' => $task->is_completed])>
                            {{ $task->title }}
                        </p>
                        @if ($task->description)
                            <p class="mt-1 whitespace-pre-line break-words text-sm text-slate-500">{{ $task->description }}</p>
                        @endif
                        @if ($task->due_date)
                            <p @class(['mt-1 text-xs', 'text-red-600 font-medium' => $task->isOverdue(), 'text-slate-400' => ! $task->isOverdue()])>
                                截止：{{ $task->due_date->format('Y-m-d') }}@if ($task->isOverdue())（已逾期）@endif
                            </p>
                        @endif
                    </div>

                    <div class="flex shrink-0 items-center gap-3 text-sm">
                        <a href="{{ route('tasks.edit', $task) }}" class="text-indigo-600 hover:underline">编辑</a>
                        <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                              onsubmit="return confirm('确定删除这个任务吗？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">删除</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="mt-6">
            {{ $tasks->links() }}
        </div>
    @endif
@endsection
