<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\Shop;

class ShopPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(Admin $admin,Shop $shop)
    {
        $admin = Admin::find(auth()->user()->id);
        return $admin->shops()->first()->id === $shop->id;
    }

}
