<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $this->canManage($user);
    }

    public function update(User $user, Product $product): bool
    {
        return $this->canManage($user);
    }

    public function delete(User $user, Product $product): bool
    {
        if ($product->stockMovements()->exists()
            || $product->purchaseOrderItems()->exists()
            || $product->saleItems()->exists()) {
            return false;
        }

        return $this->canManage($user);
    }

    public function restore(User $user, Product $product): bool
    {
        return $this->canManage($user);
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $this->canManage($user);
    }

    protected function canManage(User $user): bool
    {
        if (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo('manage-products')) {
            return true;
        }

        return (bool) ($user->is_admin ?? true);
    }
}
