<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DbiRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class DbiRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any DBI requests.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user)
    {
        // All authenticated users can view the list of DBI requests
        return true;
    }

    /**
     * Determine whether the user can view the DBI request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return bool
     */
    public function view(User $user, DbiRequest $dbiRequest)
    {
        //dd($user->isDAT());
        // Users can view a DBI request if they are the requestor, the operator, or a DAT user
        return $user->isRequester()
            || $user->isSDE()
            || $user->isDAT();
    }

    /**
     * Determine whether the user can create DBI requests.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user)
    {
        // Only Requesters and DAT users can create DBI requests
        return $user->isRequester() || $user->isDAT();
    }

    /**
     * Determine whether the user can update the DBI request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return bool
     */
    public function update(User $user, DbiRequest $dbiRequest)
    {
        // Only the requestor or a DAT user can update a DBI request
        return $user->id == $dbiRequest->requestor_id || $user->isDAT();
    }

    /**
     * Determine whether the user can delete the DBI request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return bool
     */
    public function delete(User $user, DbiRequest $dbiRequest)
    {
        // Only DAT users can delete DBI requests
        return $user->isDAT();
    }

    /**
     * Determine whether the user can execute the DBI query.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return bool
     */
    public function executeQuery(User $user, DbiRequest $dbiRequest)
    {
        // Only the requestor or a DAT user can execute the DBI query
        return $user->id == $dbiRequest->requestor_id || $user->isDAT();
    }

    /**
     * Determine whether the user can approve or reject the DBI request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return bool
     */
    public function approveOrReject(User $user, DbiRequest $dbiRequest)
    {
        // Only the assigned operator (SDE) or a DAT user can approve or reject
        return $user->id == $dbiRequest->operator_id || $user->isDAT();
    }

    /**
     * Determine whether the user can submit the DBI request to SDE.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return bool
     */
    public function submitToSDE(User $user, DbiRequest $dbiRequest)
    {
        // Only the requestor or a DAT user can submit to SDE
        return $user->id == $dbiRequest->requestor_id;
    }

    /**
     * Determine whether the user can view logs of the DBI request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return bool
     */
    public function viewLogs(User $user, DbiRequest $dbiRequest)
    {
        // Requestor, operator, and DAT users can view logs
        return $user->id == $dbiRequest->requestor_id
            || $user->id == $dbiRequest->operator_id
            || $user->isDAT();
    }

    /**
     * Determine whether the user can manage temporary tables for the DBI request.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\DbiRequest  $dbiRequest
     * @return bool
     */
    public function manageTemporaryTables(User $user, DbiRequest $dbiRequest)
    {
        // Only the requestor or a DAT user can manage temporary tables
        return $user->id === $dbiRequest->requestor_id || $user->isDAT();
    }
}