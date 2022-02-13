<?php

namespace App\Services;

use App\Services\UserModel;

class User
{
    private UserModel $user;

    public function __construct(UserModel $user, $intro = "introduction")
    {
        $this->user = $user;
    }
    public function accessUser()
    {
        return $this->user;
    }
}