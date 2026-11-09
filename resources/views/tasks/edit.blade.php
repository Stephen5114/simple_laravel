@extends('layouts.app')

@section('title', '编辑任务')

@section('content')
    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <h1 class="mb-4 text-xl font-semibold">编辑任务</h1>
        <form method="POST" action="{{ route('tasks.update', $task) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-slate-700">标题</span>
                <input type="text" name="title" value="{{ old('title', $task->title) }}" required maxlength="255"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-slate-700">描述</span>
                <textarea name="description" rows="4" maxlength="2000"
                          class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('description', $task->description) }}</textarea>
            </label>

            <label class="block">
                <span class="mb-1 block text-sm font-medium text-slate-700">截止日期</span>
                <input type="date" name="due_date" value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                       class="rounded-lg border border-slate-300 px-3 py-1.5">
            </label>

            <label class="flex items-center gap-2 text-sm text-slate-700">
                <input type="checkbox" name="is_completed" value="1" @checked(old('is_completed', $task->is_completed))
                       class="h-4 w-4 rounded border-slate-300">
                已完成
            </label>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('tasks.index') }}" class="rounded-lg px-4 py-2 text-slate-600 hover:bg-slate-100">取消</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white hover:bg-indigo-700">
                    保存
                </button>
            </div>
        </form>
    </section>
@endsection
