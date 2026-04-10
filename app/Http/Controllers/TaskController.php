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

        return view('tasks.index', [
            'tasks' => $this->taskService->getAll($status),
            'statusCounts' => $this->taskService->getStatusCounts(),
            'selectedStatus' => $status,
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

    public function destroy(Task $task): RedirectResponse
    {
        $this->taskService->delete($task->id);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
