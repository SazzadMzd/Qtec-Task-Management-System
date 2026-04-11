<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;

class TaskRepository implements TaskRepositoryInterface
{
    public function getAll(?string $status = null, ?string $focus = null, ?string $search = null): Collection
    {
        return $this->applyOrdering(
            $this->applySearch(Task::query()->status($status), $search),
            $focus
        )
            ->get();
    }

    public function getStatusCounts(?string $focus = null, ?string $search = null): array
    {
        return [
            'all' => $this->applyFocus($this->applySearch(Task::query(), $search), $focus)->count(),
            Task::STATUS_PENDING => $this->applyFocus($this->applySearch(Task::query()->status(Task::STATUS_PENDING), $search), $focus)->count(),
            Task::STATUS_IN_PROGRESS => $this->applyFocus($this->applySearch(Task::query()->status(Task::STATUS_IN_PROGRESS), $search), $focus)->count(),
            Task::STATUS_COMPLETED => $this->applyFocus($this->applySearch(Task::query()->status(Task::STATUS_COMPLETED), $search), $focus)->count(),
        ];
    }

    public function getFocusCounts(?string $status = null, ?string $search = null): array
    {
        return [
            'all' => $this->applySearch(Task::query()->status($status), $search)->count(),
            'overdue' => $this->applyFocus($this->applySearch(Task::query()->status($status), $search), 'overdue')->count(),
            'ending_soon' => $this->applyFocus($this->applySearch(Task::query()->status($status), $search), 'ending_soon')->count(),
            'newly_created' => $this->applyFocus($this->applySearch(Task::query()->status($status), $search), 'newly_created')->count(),
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

    private function applySearch(Builder $query, ?string $search): Builder
    {
        if (blank($search)) {
            return $query;
        }

        return $query->where(function (Builder $builder) use ($search) {
            $builder
                ->where('title', 'like', '%' . $search . '%')
                ->orWhere('assigned_to', 'like', '%' . $search . '%');
        });
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
