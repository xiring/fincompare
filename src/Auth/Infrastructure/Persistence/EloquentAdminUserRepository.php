<?php

namespace Src\Auth\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Application\DTOs\AdminUserDTO;
use Src\Auth\Domain\Entities\User;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;
use Src\Shared\Application\Criteria\ListCriteria;

/**
 * EloquentAdminUserRepository repository.
 */
class EloquentAdminUserRepository implements AdminUserRepositoryInterface
{
    public function paginate(ListCriteria $criteria): LengthAwarePaginator
    {
        $sort = in_array(($criteria->getSort() ?? ''), ['id', 'name', 'email', 'created_at']) ? $criteria->getSort() : 'id';
        $dir = $criteria->getDir();
        $filters = $criteria->filters();
        $perPage = $criteria->getPerPage() ?? 20;

        return User::query()
            ->when($criteria->getSearch(), function ($q, $qStr) {
                $q->where('name', 'like', '%'.$qStr.'%')->orWhere('email', 'like', '%'.$qStr.'%');
            })
            ->orderBy($sort, $dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(AdminUserDTO $dto): User
    {
        $user = User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => $dto->password ?? 'password',
        ]);
        if (! empty($dto->roles)) {
            $user->syncRoles($dto->roles);
        }

        return $user;
    }

    public function update(User $user, AdminUserDTO $dto): User
    {
        $payload = ['name' => $dto->name, 'email' => $dto->email];
        if (! empty($dto->password)) {
            $payload['password'] = $dto->password;
        }
        $user->update($payload);
        $user->syncRoles($dto->roles ?? []);

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
