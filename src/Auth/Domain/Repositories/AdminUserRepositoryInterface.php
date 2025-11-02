<?php
namespace Src\Auth\Domain\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Domain\Entities\User;
use Src\Auth\Application\DTOs\AdminUserDTO;

interface AdminUserRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;
    public function find(int $id): ?User;
    public function create(AdminUserDTO $dto): User;
    public function update(User $user, AdminUserDTO $dto): User;
    public function delete(User $user): void;
}


