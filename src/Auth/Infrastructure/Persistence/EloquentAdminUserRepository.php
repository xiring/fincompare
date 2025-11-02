<?php
namespace Src\Auth\Infrastructure\Persistence;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Src\Auth\Application\DTOs\AdminUserDTO;
use Src\Auth\Domain\Entities\User;
use Src\Auth\Domain\Repositories\AdminUserRepositoryInterface;

class EloquentAdminUserRepository implements AdminUserRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $sort = in_array(($filters['sort'] ?? ''), ['id','name','email','created_at']) ? $filters['sort'] : 'id';
        $dir = strtolower($filters['dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';
        return User::query()
            ->when(($filters['q'] ?? null), function($q,$qStr){
                $q->where('name','like','%'.$qStr.'%')->orWhere('email','like','%'.$qStr.'%');
            })
            ->orderBy($sort,$dir)
            ->paginate($perPage)->withQueryString();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(AdminUserDTO $dto): User
    {
        $user = User::create([
            'name'=>$dto->name,
            'email'=>$dto->email,
            'password'=>$dto->password ?? 'password',
        ]);
        if (!empty($dto->roles)) $user->syncRoles($dto->roles);
        return $user;
    }

    public function update(User $user, AdminUserDTO $dto): User
    {
        $payload = ['name'=>$dto->name,'email'=>$dto->email];
        if (!empty($dto->password)) $payload['password'] = $dto->password;
        $user->update($payload);
        $user->syncRoles($dto->roles ?? []);
        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}


