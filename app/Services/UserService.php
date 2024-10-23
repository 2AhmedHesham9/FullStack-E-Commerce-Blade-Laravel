<?php

namespace App\Services;

use App\Models\User;

class UserService
{

    public function get_Users_For_admin()
    {
        $users = User::select('id', 'name', 'utype', 'mobile', 'email')->with(['orders:id,user_id'])->orderBy('created_at', 'DESC')->paginate(15);
        return ['users' => $users];
    }
}
