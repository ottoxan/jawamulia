<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\WorkingStation;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkingStationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:WorkingStation');
    }

    public function view(AuthUser $authUser, WorkingStation $workingStation): bool
    {
        return $authUser->can('View:WorkingStation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:WorkingStation');
    }

    public function update(AuthUser $authUser, WorkingStation $workingStation): bool
    {
        return $authUser->can('Update:WorkingStation');
    }

    public function delete(AuthUser $authUser, WorkingStation $workingStation): bool
    {
        return $authUser->can('Delete:WorkingStation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:WorkingStation');
    }

    public function restore(AuthUser $authUser, WorkingStation $workingStation): bool
    {
        return $authUser->can('Restore:WorkingStation');
    }

    public function forceDelete(AuthUser $authUser, WorkingStation $workingStation): bool
    {
        return $authUser->can('ForceDelete:WorkingStation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:WorkingStation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:WorkingStation');
    }

    public function replicate(AuthUser $authUser, WorkingStation $workingStation): bool
    {
        return $authUser->can('Replicate:WorkingStation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:WorkingStation');
    }

}