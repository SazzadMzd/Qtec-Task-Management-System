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

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            {{ $isEditing ? 'Save Changes' : 'Create Task' }}
        </button>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</div>
