<?php
namespace Src\Partners\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Src\Partners\Domain\Entities\Partner;

class PartnerPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Partner $partner): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Partner $partner): bool { return true; }
    public function delete(User $user, Partner $partner): bool { return method_exists($user,'hasRole') && $user->hasRole('admin'); }
}


