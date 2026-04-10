<?php

namespace App\Interfaces;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getAll(?string $status = null, ?string $focus = null): Collection;

    public function getStatusCounts(?string $focus = null): array;

    public function getFocusCounts(?string $status = null): array;

    public function findById(int $id): ?Task;

    public function create(array $data): Task;

    public function update(int $id, array $data): Task;

    public function delete(int $id): bool;
}
