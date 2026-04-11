@extends('layouts.app', [
    'title' => 'Tasks | ' . config('app.name'),
    'eyebrowHref' => url('/'),
    'heading' => 'Task Management Board',
    'subheading' => 'Track planned work, active work, and completed work in one clear workspace with scheduling built in.',
    'heroMetrics' => [['value' => $globalStatusCounts['all'], 'label' => 'Total tasks'], ['value' => $globalStatusCounts[\App\Models\Task::STATUS_PENDING], 'label' => 'Pending'], ['value' => $globalStatusCounts[\App\Models\Task::STATUS_IN_PROGRESS], 'label' => 'In Progress'], ['value' => $globalStatusCounts[\App\Models\Task::STATUS_COMPLETED], 'label' => 'Completed']],
])

@section('content')
    <div class="panel-head">
        <div>
            <h2 class="panel-title">Task Overview</h2>
            <p class="panel-subtitle">Review the full task pipeline, filter by status, and check task schedules without
                leaving the page flow.</p>
        </div>

        <a href="{{ route('tasks.create') }}" class="btn btn-primary">Create New Task</a>
    </div>

    <section class="filter-toolbar">
        <div class="filter-toolbar-head">
            <div>
                <h3 class="filter-toolbar-title">Browse Tasks Faster</h3>
            </div>
        </div>

        <form method="GET" action="{{ route('tasks.index') }}" class="filter-toolbar-form">
            <div class="filter-field">
                <label for="search">Search tasks</label>
                <input id="search" name="search" type="search" class="control" value="{{ $selectedSearch }}"
                    placeholder="Search by title or assignee" autocomplete="off" data-live-search>
            </div>

            <div class="filter-field">
                <label for="status">Status view</label>
                <select id="status" name="status" class="control">
                    <option value="">All tasks ({{ $statusCounts['all'] }})</option>
                    <option value="{{ \App\Models\Task::STATUS_PENDING }}" @selected($selectedStatus === \App\Models\Task::STATUS_PENDING)>
                        Pending ({{ $statusCounts[\App\Models\Task::STATUS_PENDING] }})
                    </option>
                    <option value="{{ \App\Models\Task::STATUS_IN_PROGRESS }}" @selected($selectedStatus === \App\Models\Task::STATUS_IN_PROGRESS)>
                        In Progress ({{ $statusCounts[\App\Models\Task::STATUS_IN_PROGRESS] }})
                    </option>
                    <option value="{{ \App\Models\Task::STATUS_COMPLETED }}" @selected($selectedStatus === \App\Models\Task::STATUS_COMPLETED)>
                        Completed ({{ $statusCounts[\App\Models\Task::STATUS_COMPLETED] }})
                    </option>
                </select>
            </div>

            <div class="filter-field">
                <label for="focus">Time view</label>
                <select id="focus" name="focus" class="control">
                    <option value="">All timing views ({{ $focusCounts['all'] }})</option>
                    <option value="overdue" @selected($selectedFocus === 'overdue')>
                        Overdue ({{ $focusCounts['overdue'] }})
                    </option>
                    <option value="ending_soon" @selected($selectedFocus === 'ending_soon')>
                        Nearing End ({{ $focusCounts['ending_soon'] }})
                    </option>
                    <option value="newly_created" @selected($selectedFocus === 'newly_created')>
                        Newly Created ({{ $focusCounts['newly_created'] }})
                    </option>
                </select>
            </div>

            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">Apply Filters</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </section>

    @if ($tasks->isEmpty())
        <div class="empty-state">
            <div class="eyebrow" style="background: var(--brand-soft); color: var(--brand-deep);">No Tasks Yet</div>
            <h3>Build the board from the first task.</h3>
            <p>
                There are no tasks in this view right now. Add one with a clear schedule so the rest of the workflow has
                something concrete to move through the system.
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
                            <span class="meta-chip">Assigned to {{ $task->assigned_to ?: 'Unassigned' }}</span>
                            <span class="meta-chip">
                                {{ $task->start_time ? $task->start_time->format('d M Y, h:i A') : 'Schedule not set' }}
                                @if ($task->end_time)
                                    to {{ $task->end_time->format('d M Y, h:i A') }}
                                @endif
                            </span>
                            @if ($task->status !== \App\Models\Task::STATUS_COMPLETED && $task->end_time && $task->end_time->isFuture())
                                <span class="meta-chip">Ends in {{ $task->end_time->diffForHumans() }}</span>
                            @endif
                            <span class="meta-chip">Created {{ $task->created_at->format('d M Y, h:i A') }}</span>
                            <span class="meta-chip">Updated {{ $task->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    <div class="task-actions">
                        <form method="POST" action="{{ route('tasks.update-status', $task) }}" class="status-inline-form" data-auto-submit>
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status_filter" value="{{ $selectedStatus }}">
                            <input type="hidden" name="focus_filter" value="{{ $selectedFocus }}">
                            <input type="hidden" name="search_filter" value="{{ $selectedSearch }}">
                            <select name="status" class="control" aria-label="Update task status for {{ $task->title }}">
                                @foreach ($statuses as $status)
                                    <option value="{{ $status }}" @selected($task->status === $status)>
                                        {{ str($status)->replace('_', ' ')->title() }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-secondary icon-btn" title="Edit task" aria-label="Edit task">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 20h4l10-10-4-4L4 16v4Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="m12.5 7.5 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('tasks.destroy', $task) }}" data-confirm
                            data-confirm-title="Delete this task?"
                            data-confirm-text="This action will permanently remove the task from the board."
                            data-confirm-button="Delete task" data-cancel-button="Keep task">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger icon-btn" title="Delete task" aria-label="Delete task">
                                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M5 7h14" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M10 11v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M14 11v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                                    <path d="M7 7l1 12h8l1-12" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    <path d="M9.5 7V4.5h5V7" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    @endif
@endsection
