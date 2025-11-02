<?php
namespace Src\Catalog\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Src\Catalog\Domain\Entities\ProductCategory;

class ProductCategoryPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, ProductCategory $model): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, ProductCategory $model): bool { return true; }
    public function delete(User $user, ProductCategory $model): bool { return method_exists($user,'hasRole') && $user->hasRole('admin'); }
}


