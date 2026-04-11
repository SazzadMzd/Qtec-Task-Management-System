@extends('layouts.app', [
    'title' => 'Create Task | ' . config('app.name'),
    'eyebrowHref' => url('/'),
    'heading' => 'Create a Task',
    'subheading' => 'Capture the task clearly and define its schedule so progress tracking and handoffs stay easy later.',
    'heroMetrics' => [
        ['value' => 'Pending', 'label' => 'Default status'],
        ['value' => 'Start + End', 'label' => 'Required schedule'],
        ['value' => '1 Form', 'label' => 'Fast entry'],
    ],
])

@section('content')
    <div class="panel-head">
        <div>
            <h2 class="panel-title">New Task</h2>
            <p class="panel-subtitle">Add the title, optional context, status, and the task schedule.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        @include('tasks._form')
    </form>
@endsection
