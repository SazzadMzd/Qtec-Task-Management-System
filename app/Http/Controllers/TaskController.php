<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    ) {
    }

    public function index(Request $request): View
    {
        $status = $request->query('status');
        $focus = $request->query('focus');
        $search = $request->query('search');

        return view('tasks.index', [
            'tasks' => $this->taskService->getAll($status, $focus, $search),
            'statusCounts' => $this->taskService->getStatusCounts($focus, $search),
            'focusCounts' => $this->taskService->getFocusCounts($status, $search),
            'globalStatusCounts' => $this->taskService->getStatusCounts(),
            'globalFocusCounts' => $this->taskService->getFocusCounts(),
            'selectedStatus' => $status,
            'selectedFocus' => $focus,
            'selectedSearch' => $search,
            'statuses' => Task::statuses(),
        ]);
    }

    public function create(): View
    {
        return view('tasks.create', [
            'task' => new Task([
                'status' => Task::STATUS_PENDING,
            ]),
            'statuses' => Task::statuses(),
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->taskService->create($request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    public function edit(Task $task): View
    {
        return view('tasks.edit', [
            'task' => $task,
            'statuses' => Task::statuses(),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $this->taskService->update($task->id, $request->validated());

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function updateStatus(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', \Illuminate\Validation\Rule::in(Task::statuses())],
            'status_filter' => ['nullable', 'string'],
            'focus_filter' => ['nullable', 'string'],
            'search_filter' => ['nullable', 'string'],
        ]);

        $this->taskService->updateStatus($task->id, $validated['status']);

        return redirect()
            ->route('tasks.index', array_filter([
                'status' => $validated['status_filter'] ?? null,
                'focus' => $validated['focus_filter'] ?? null,
                'search' => $validated['search_filter'] ?? null,
            ]))
            ->with('success', 'Task status updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->taskService->delete($task->id);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
