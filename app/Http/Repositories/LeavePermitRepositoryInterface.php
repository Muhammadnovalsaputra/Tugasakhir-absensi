<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface LeavePermitRepositoryInterface
{
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function find(int $id);
    public function findForUpdate(int $id);
    public function findUserPendingLeave(int $leaveId, int $userId);
    public function getUserLeaveHistory(int $userId): Collection;
    public function getUsedQuota(int $userId): int;
    public function getAllWithUser(): LengthAwarePaginator;
    public function getStatusCounts(): array;
}