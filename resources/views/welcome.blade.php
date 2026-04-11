@extends('layouts.app', [
    'title' => 'Welcome | ' . config('app.name'),
    'eyebrow' => 'Qtec Task Management System',
    'eyebrowHref' => url('/'),
    'heading' => 'Manage every task from one calm starting point',
    'subheading' => 'Plan work, assign ownership, track deadlines, and move tasks forward without jumping through a complex setup.',
    'heroMetrics' => [['value' => 'Tasks', 'label' => 'General work tracking'], ['value' => 'Status', 'label' => 'Pending to completed'], ['value' => 'Time', 'label' => 'Start and end schedule'], ['value' => 'Flow', 'label' => 'Fast daily updates']],
])

@section('content')
    <div class="landing-grid">
        <section class="landing-card">
            <h2>Start with the board or create a task right away</h2>
            <p>
                This system is built to keep task management straightforward. You can open the board to review the full
                pipeline, or jump straight into creating a new task with assignee, status, and schedule.
            </p>

            <div class="landing-actions">
                <a href="{{ route('tasks.index') }}" class="btn btn-primary">Open Task Board</a>
                <a href="{{ route('tasks.create') }}" class="btn btn-secondary">Create a Task</a>
            </div>

            <div class="feature-list">
                <div class="feature-item">
                    <span class="feature-icon">1</span>
                    <div class="feature-copy">
                        <strong>Capture the work clearly</strong>
                        <span>Add title, description, assignee, and a realistic time window so the task is ready to
                            track.</span>
                    </div>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">2</span>
                    <div class="feature-copy">
                        <strong>Review what needs attention</strong>
                        <span>Use filters to focus on pending, in progress, overdue, nearing-end, or newly created
                            tasks.</span>
                    </div>
                </div>

                <div class="feature-item">
                    <span class="feature-icon">3</span>
                    <div class="feature-copy">
                        <strong>Update progress quickly</strong>
                        <span>Change status directly from the board and keep the team’s view current with less
                            friction.</span>
                    </div>
                </div>
            </div>
        </section>

        <div class="landing-stack">
            <section class="landing-card">
                <h3>What this app helps with</h3>
                <p>
                    It works well for general task tracking, small team coordination, and practical deadline visibility in
                    one lightweight Laravel application.
                </p>

                <div class="landing-stat-grid">
                    <div class="landing-stat">
                        <strong>Assigned</strong>
                        <span>Clear ownership for every task</span>
                    </div>
                    <div class="landing-stat">
                        <strong>Scheduled</strong>
                        <span>Start and end time on each item</span>
                    </div>
                    <div class="landing-stat">
                        <strong>Filtered</strong>
                        <span>Find work by status or urgency</span>
                    </div>
                    <div class="landing-stat">
                        <strong>Tested</strong>
                        <span>Core flows covered with feature tests</span>
                    </div>
                </div>
            </section>

            <section class="landing-card">
                <h3>Quick first steps</h3>

                <div class="quick-steps">
                    <div class="step-item">
                        <span class="step-number">1</span>
                        <div class="step-copy">
                            <strong>Create a task</strong>
                            <span>Add the title, assignee, and schedule so it can be tracked properly.</span>
                        </div>
                    </div>

                    <div class="step-item">
                        <span class="step-number">2</span>
                        <div class="step-copy">
                            <strong>Open the board</strong>
                            <span>See all tasks together and narrow the view with filters when needed.</span>
                        </div>
                    </div>

                    <div class="step-item">
                        <span class="step-number">3</span>
                        <div class="step-copy">
                            <strong>Keep statuses fresh</strong>
                            <span>Update items from the index page as work moves from pending to completed.</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    {{-- Developer Credit --}}
    <footer class="developer-credit">
        <p>
            Developed by <strong>Sazzad Mazumder</strong>
        </p>
    </footer>
@endsection
