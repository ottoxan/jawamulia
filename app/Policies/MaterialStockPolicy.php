<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MaterialStock;
use Illuminate\Auth\Access\HandlesAuthorization;

class MaterialStockPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MaterialStock');
    }

    public function view(AuthUser $authUser, MaterialStock $materialStock): bool
    {
        return $authUser->can('View:MaterialStock');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MaterialStock');
    }

    public function update(AuthUser $authUser, MaterialStock $materialStock): bool
    {
        return $authUser->can('Update:MaterialStock');
    }

    public function delete(AuthUser $authUser, MaterialStock $materialStock): bool
    {
        return $authUser->can('Delete:MaterialStock');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MaterialStock');
    }

    public function restore(AuthUser $authUser, MaterialStock $materialStock): bool
    {
        return $authUser->can('Restore:MaterialStock');
    }

    public function forceDelete(AuthUser $authUser, MaterialStock $materialStock): bool
    {
        return $authUser->can('ForceDelete:MaterialStock');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MaterialStock');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MaterialStock');
    }

    public function replicate(AuthUser $authUser, MaterialStock $materialStock): bool
    {
        return $authUser->can('Replicate:MaterialStock');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MaterialStock');
    }

}