<?php

namespace Apps\Policies\Store;

use Apps\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Apps\Models\Store\SalesOrder;

class PurchaceOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function owner(User $user, SalesOrder $salesOrder){

        // if ($user->roles->whereIn('id', 1)->isNotEmpty()) {
        //     return true;
        // }

        return $salesOrder->customer_id === $user->userable->id;
    }
}
