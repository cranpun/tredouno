<?php

namespace App\L;

class Role extends ZzzLabel
{
    const ID_ADMIN = "admin";

    public function labels()
    {
        return [
            self::ID_ADMIN => "管理者",
        ];
    }
}
