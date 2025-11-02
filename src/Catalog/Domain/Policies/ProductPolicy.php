<?php
namespace Src\Catalog\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Src\Catalog\Domain\Entities\Product;

class ProductPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Product $model): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Product $model): bool { return true; }
    public function delete(User $user, Product $model): bool { return method_exists($user,'hasRole') && $user->hasRole('admin'); }
}


