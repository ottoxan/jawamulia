<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ProductTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class ProductTransactionPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ProductTransaction');
    }

    public function view(AuthUser $authUser, ProductTransaction $productTransaction): bool
    {
        return $authUser->can('View:ProductTransaction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ProductTransaction');
    }

    public function update(AuthUser $authUser, ProductTransaction $productTransaction): bool
    {
        return $authUser->can('Update:ProductTransaction');
    }

    public function delete(AuthUser $authUser, ProductTransaction $productTransaction): bool
    {
        return $authUser->can('Delete:ProductTransaction');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ProductTransaction');
    }

    public function restore(AuthUser $authUser, ProductTransaction $productTransaction): bool
    {
        return $authUser->can('Restore:ProductTransaction');
    }

    public function forceDelete(AuthUser $authUser, ProductTransaction $productTransaction): bool
    {
        return $authUser->can('ForceDelete:ProductTransaction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ProductTransaction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ProductTransaction');
    }

    public function replicate(AuthUser $authUser, ProductTransaction $productTransaction): bool
    {
        return $authUser->can('Replicate:ProductTransaction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ProductTransaction');
    }
}
