@php
    $isEditing = $task->exists;
@endphp

<div class="form-grid">
    <div class="field">
        <label for="title">Task title</label>
        <input
            id="title"
            name="title"
            type="text"
            class="control"
            value="{{ old('title', $task->title) }}"
            placeholder="Write a short, actionable task title"
            required
        >
        <span class="field-note">Keep it specific enough that anyone can understand the next action.</span>
        @error('title')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="description">Description</label>
        <textarea
            id="description"
            name="description"
            class="control"
            placeholder="Add context, acceptance notes, or anything the team should know"
        >{{ old('description', $task->description) }}</textarea>
        @error('description')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="assigned_to">Assigned To</label>
        <input
            id="assigned_to"
            name="assigned_to"
            type="text"
            class="control"
            value="{{ old('assigned_to', $task->assigned_to) }}"
            placeholder="Enter assignee name"
            required
        >
        <span class="field-note">Specify who is responsible for handling this task.</span>
        @error('assigned_to')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="status">Status</label>
        <select id="status" name="status" class="control" required>
            @foreach ($statuses as $status)
                <option value="{{ $status }}" @selected(old('status', $task->status) === $status)>
                    {{ str($status)->replace('_', ' ')->title() }}
                </option>
            @endforeach
        </select>
        <span class="field-note">Choose the current state so the list stays meaningful.</span>
        @error('status')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="start_time">Start time</label>
        <input
            id="start_time"
            name="start_time"
            type="datetime-local"
            class="control"
            value="{{ old('start_time', $task->start_time?->format('Y-m-d\\TH:i')) }}"
            required
        >
        <span class="field-note">Set when the task is planned to begin.</span>
        @error('start_time')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="field">
        <label for="end_time">End time</label>
        <input
            id="end_time"
            name="end_time"
            type="datetime-local"
            class="control"
            value="{{ old('end_time', $task->end_time?->format('Y-m-d\\TH:i')) }}"
            required
        >
        <span class="field-note">Set when the task should be completed.</span>
        @error('end_time')
            <div class="field-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            {{ $isEditing ? 'Save Changes' : 'Create Task' }}
        </button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</div>
