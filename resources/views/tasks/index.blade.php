@php
    $pendingCount = $tasks->where('status', \App\Models\Task::STATUS_PENDING)->count();
    $inProgressCount = $tasks->where('status', \App\Models\Task::STATUS_IN_PROGRESS)->count();
    $completedCount = $tasks->where('status', \App\Models\Task::STATUS_COMPLETED)->count();
@endphp

@extends('layouts.app', [
    'title' => 'Tasks | ' . config('app.name'),
    'heading' => 'Daily Task Board',
    'subheading' => 'Track what needs attention, what is actively moving, and what is already finished in one clean workspace.',
    'heroMetrics' => [
        ['value' => $tasks->count(), 'label' => 'Visible tasks'],
        ['value' => $selectedStatus ? str($selectedStatus)->replace('_', ' ')->title() : 'All', 'label' => 'Current view'],
        ['value' => $completedCount, 'label' => 'Completed'],
    ],
])

@section('content')
    <div class="panel-head">
        <div>
            <h2 class="panel-title">Task Overview</h2>
            <p class="panel-subtitle">Review the current workload, filter by status, and jump into edits without leaving the page flow.</p>
        </div>

        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create New Task</a>
    </div>

    <div class="filters" style="margin-bottom: 22px;">
        <a href="{{ route('tasks.index') }}" class="filter-pill {{ $selectedStatus ? '' : 'active' }}">
            All Tasks
            <span>{{ $tasks->count() }}</span>
        </a>
        <a href="{{ route('tasks.index', ['status' => \App\Models\Task::STATUS_PENDING]) }}" class="filter-pill {{ $selectedStatus === \App\Models\Task::STATUS_PENDING ? 'active' : '' }}">
            Pending
            <span>{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('tasks.index', ['status' => \App\Models\Task::STATUS_IN_PROGRESS]) }}" class="filter-pill {{ $selectedStatus === \App\Models\Task::STATUS_IN_PROGRESS ? 'active' : '' }}">
            In Progress
            <span>{{ $inProgressCount }}</span>
        </a>
        <a href="{{ route('tasks.index', ['status' => \App\Models\Task::STATUS_COMPLETED]) }}" class="filter-pill {{ $selectedStatus === \App\Models\Task::STATUS_COMPLETED ? 'active' : '' }}">
            Completed
            <span>{{ $completedCount }}</span>
        </a>
    </div>

    @if ($tasks->isEmpty())
        <div class="empty-state">
            <div class="eyebrow" style="background: var(--brand-soft); color: var(--brand-deep);">No Tasks Yet</div>
            <h3>Build the board from the first task.</h3>
            <p>
                There are no tasks in this view right now. Start with one clear task so the rest of the workflow has something concrete to move through the system.
            </p>
            <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create the First Task</a>
        </div>
    @else
        <div class="task-grid">
            @foreach ($tasks as $task)
                @php
                    $badgeClass = match ($task->status) {
                        \App\Models\Task::STATUS_IN_PROGRESS => 'badge badge-in-progress',
                        \App\Models\Task::STATUS_COMPLETED => 'badge badge-completed',
                        default => 'badge badge-pending',
                    };
                @endphp

                <article class="task-card">
                    <div>
                        <span class="{{ $badgeClass }}">{{ str($task->status)->replace('_', ' ')->title() }}</span>
                        <h3>{{ $task->title }}</h3>
                        <p>{{ $task->description ?: 'No extra description was added for this task yet.' }}</p>

                        <div class="task-meta">
                            <span class="meta-chip">Created {{ $task->created_at->format('d M Y, h:i A') }}</span>
                            <span class="meta-chip">Updated {{ $task->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="task-actions">
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary">Edit</a>

                        <form
                            method="POST"
                            action="{{ route('tasks.destroy', $task) }}"
                            data-confirm
                            data-confirm-title="Delete this task?"
                            data-confirm-text="This action will permanently remove the task from the board."
                            data-confirm-button="Delete task"
                            data-cancel-button="Keep task"
                        >
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
