@extends('layouts.app', [
    'title' => 'Edit Task | ' . config('app.name'),
    'heading' => 'Refine Task Details',
    'subheading' => 'Update the task information, schedule, and status so the board stays aligned with the actual progress of work.',
    'heroMetrics' => [
        ['value' => $task->id, 'label' => 'Task ID'],
        ['value' => str($task->status)->replace('_', ' ')->title(), 'label' => 'Current status'],
        ['value' => $task->updated_at?->diffForHumans() ?? 'Now', 'label' => 'Last update'],
    ],
])

@section('content')
    <div class="panel-head">
        <div>
            <h2 class="panel-title">Edit Task</h2>
            <p class="panel-subtitle">Adjust the task details and schedule without losing the existing workflow context.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('tasks.update', $task) }}">
        @csrf
        @method('PUT')
        @include('tasks._form')
    </form>
@endsection
