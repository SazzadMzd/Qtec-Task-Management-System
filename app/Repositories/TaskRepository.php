<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(?string $status = null, ?string $focus = null): Collection
    {
        return $this->applyOrdering(
            Task::query()->status($status),
            $focus
        )
            ->get();
    }

    public function getStatusCounts(?string $focus = null): array
    {
        return [
            'all' => $this->applyFocus(Task::query(), $focus)->count(),
            Task::STATUS_PENDING => $this->applyFocus(Task::query()->status(Task::STATUS_PENDING), $focus)->count(),
            Task::STATUS_IN_PROGRESS => $this->applyFocus(Task::query()->status(Task::STATUS_IN_PROGRESS), $focus)->count(),
            Task::STATUS_COMPLETED => $this->applyFocus(Task::query()->status(Task::STATUS_COMPLETED), $focus)->count(),
        ];
    }

    public function getFocusCounts(?string $status = null): array
    {
        return [
            'all' => Task::query()->status($status)->count(),
            'overdue' => $this->applyFocus(Task::query()->status($status), 'overdue')->count(),
            'ending_soon' => $this->applyFocus(Task::query()->status($status), 'ending_soon')->count(),
            'newly_created' => $this->applyFocus(Task::query()->status($status), 'newly_created')->count(),
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

    private function applyFocus(Builder $query, ?string $focus): Builder
    {
        $now = Carbon::now();

        return match ($focus) {
            'overdue' => $query
                ->where('status', '!=', Task::STATUS_COMPLETED)
                ->where('end_time', '<', $now),
            'ending_soon' => $query
                ->where('status', '!=', Task::STATUS_COMPLETED)
                ->where('end_time', '>=', $now),
            'newly_created' => $query
                ->where('created_at', '>=', $now->copy()->subDay()),
            default => $query,
        };
    }

    private function applyOrdering(Builder $query, ?string $focus): Builder
    {
        $query = $this->applyFocus($query, $focus);

        return match ($focus) {
            'ending_soon' => $query->orderBy('end_time')->orderBy('start_time'),
            'newly_created' => $query->latest(),
            'overdue' => $query->orderByDesc('end_time'),
            default => $query->latest(),
        };
    }
}
