<?php

namespace App\Services;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    private const ALLOWED_FOCUS_FILTERS = [
        'overdue',
        'ending_soon',
        'newly_created',
    ];

    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function getAll(?string $status = null, ?string $focus = null, ?string $search = null): Collection
    {
        return $this->taskRepository->getAll(
            $this->normalizeStatus($status),
            $this->normalizeFocus($focus),
            $this->normalizeSearch($search)
        );
    }

    public function getStatusCounts(?string $focus = null, ?string $search = null): array
    {
        return $this->taskRepository->getStatusCounts(
            $this->normalizeFocus($focus),
            $this->normalizeSearch($search)
        );
    }

    public function getFocusCounts(?string $status = null, ?string $search = null): array
    {
        return $this->taskRepository->getFocusCounts(
            $this->normalizeStatus($status),
            $this->normalizeSearch($search)
        );
    }

    public function findById(int $id): ?Task
    {
        return $this->taskRepository->findById($id);
    }

    public function create(array $data): Task
    {
        return $this->taskRepository->create($this->preparePayload($data));
    }

    public function update(int $id, array $data): Task
    {
        return $this->taskRepository->update($id, $this->preparePayload($data));
    }

    public function updateStatus(int $id, string $status): Task
    {
        return $this->taskRepository->update($id, [
            'status' => $this->normalizeStatus($status) ?? Task::STATUS_PENDING,
        ]);
    }

    public function delete(int $id): bool
    {
        return $this->taskRepository->delete($id);
    }

    private function preparePayload(array $data): array
    {
        return [
            'title' => trim($data['title']),
            'description' => blank($data['description'] ?? null)
                ? null
                : trim($data['description']),
            'assigned_to' => trim($data['assigned_to']),
            'status' => $this->normalizeStatus($data['status'] ?? null) ?? Task::STATUS_PENDING,
            'start_time' => blank($data['start_time'] ?? null) ? null : $data['start_time'],
            'end_time' => blank($data['end_time'] ?? null) ? null : $data['end_time'],
        ];
    }

    private function normalizeStatus(?string $status): ?string
    {
        if (blank($status) || !in_array($status, Task::statuses(), true)) {
            return null;
        }

        return $status;
    }

    private function normalizeFocus(?string $focus): ?string
    {
        if (blank($focus) || !in_array($focus, self::ALLOWED_FOCUS_FILTERS, true)) {
            return null;
        }

        return $focus;
    }

    private function normalizeSearch(?string $search): ?string
    {
        if (blank($search)) {
            return null;
        }

        return trim($search);
    }
}
