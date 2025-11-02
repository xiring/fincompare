<?php
namespace Src\Leads\Domain\Policies;

use Src\Auth\Domain\Entities\User;
use Src\Leads\Domain\Entities\Lead;

class LeadPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, Lead $model): bool { return true; }
    public function create(User $user): bool { return true; }
    public function update(User $user, Lead $model): bool { return true; }
    public function delete(User $user, Lead $model): bool { return method_exists($user,'hasRole') && $user->hasRole('admin'); }
}


