<?php

namespace App\Policies;

use App\Enums\OpnameStatus;
use App\Models\StockOpname;
use App\Models\User;

class StockOpnamePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, StockOpname $opname): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->canOperate($user);
    }

    public function update(User $user, StockOpname $opname): bool
    {
        if (in_array($opname->status, [OpnameStatus::COMPLETED->value, OpnameStatus::CANCELLED->value], true)) {
            return false;
        }

        return $this->canOperate($user);
    }

    public function count(User $user, StockOpname $opname): bool
    {
        return $opname->status === OpnameStatus::IN_PROGRESS->value
            && $this->canOperate($user);
    }

    public function approve(User $user, StockOpname $opname): bool
    {
        return $opname->status === OpnameStatus::COMPLETED->value
            && $this->canApprove($user);
    }

    public function delete(User $user, StockOpname $opname): bool
    {
        return $opname->status === OpnameStatus::DRAFT->value
            && $this->canOperate($user);
    }

    protected function canOperate(User $user): bool
    {
        if (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('manage-stock-opname')) {
            return true;
        }

        return (bool) ($user->is_admin ?? true);
    }

    protected function canApprove(User $user): bool
    {
        if (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('approve-stock-opname')) {
            return true;
        }

        return (bool) ($user->is_admin ?? true);
    }
}
