<?php

namespace App\Services;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function getAll(?string $status = null): Collection
    {
        return $this->taskRepository->getAll($this->normalizeStatus($status));
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
            'status' => $this->normalizeStatus($data['status'] ?? null) ?? Task::STATUS_PENDING,
        ];
    }

    private function normalizeStatus(?string $status): ?string
    {
        if (blank($status) || ! in_array($status, Task::statuses(), true)) {
            return null;
        }

        return $status;
    }
}
