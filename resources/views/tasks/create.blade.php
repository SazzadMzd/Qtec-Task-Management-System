@extends('layouts.app', [
    'title' => 'Create Task | ' . config('app.name'),
    'heading' => 'Create a Fresh Task',
    'subheading' => 'Capture the task clearly now so status updates and handoffs stay easy later.',
    'heroMetrics' => [
        ['value' => 'Pending', 'label' => 'Default status'],
        ['value' => '1 Form', 'label' => 'Fast entry'],
        ['value' => 'CRUD', 'label' => 'Core workflow'],
    ],
])

@section('content')
    <div class="panel-head">
        <div>
            <h2 class="panel-title">New Task</h2>
            <p class="panel-subtitle">Add the title, optional context, and the right starting status.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('tasks.store') }}">
        @csrf
        @include('tasks._form')
    </form>
@endsection
