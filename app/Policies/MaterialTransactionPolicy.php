<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MaterialTransaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialTransactionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MaterialTransaction');
    }

    public function view(AuthUser $authUser, MaterialTransaction $materialTransaction): bool
    {
        return $authUser->can('View:MaterialTransaction');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MaterialTransaction');
    }

    public function update(AuthUser $authUser, MaterialTransaction $materialTransaction): bool
    {
        return $authUser->can('Update:MaterialTransaction');
    }

    public function delete(AuthUser $authUser, MaterialTransaction $materialTransaction): bool
    {
        return $authUser->can('Delete:MaterialTransaction');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MaterialTransaction');
    }

    public function restore(AuthUser $authUser, MaterialTransaction $materialTransaction): bool
    {
        return $authUser->can('Restore:MaterialTransaction');
    }

    public function forceDelete(AuthUser $authUser, MaterialTransaction $materialTransaction): bool
    {
        return $authUser->can('ForceDelete:MaterialTransaction');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MaterialTransaction');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MaterialTransaction');
    }

    public function replicate(AuthUser $authUser, MaterialTransaction $materialTransaction): bool
    {
        return $authUser->can('Replicate:MaterialTransaction');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MaterialTransaction');
    }

}