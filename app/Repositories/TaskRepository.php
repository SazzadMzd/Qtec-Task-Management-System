<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(?string $status = null): Collection
    {
        return Task::query()
            ->status($status)
            ->latest()
            ->get();
    }

    public function getStatusCounts(): array
    {
        return [
            'all' => Task::count(),
            Task::STATUS_PENDING => Task::query()->status(Task::STATUS_PENDING)->count(),
            Task::STATUS_IN_PROGRESS => Task::query()->status(Task::STATUS_IN_PROGRESS)->count(),
            Task::STATUS_COMPLETED => Task::query()->status(Task::STATUS_COMPLETED)->count(),
        ];
    }

    public function findById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);

        return $task->refresh();
    }

    public function delete(int $id): bool
    {
        $task = Task::findOrFail($id);

        return (bool) $task->delete();
    }
}
