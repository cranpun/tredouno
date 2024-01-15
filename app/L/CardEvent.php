<?php

namespace App\L;

class CardEvent extends ZzzLabel
{
    const ID_DRAW = "draw";
    const ID_WILD = "wild";
    const ID_WILD4 = "wild4";
    const ID_END = "end";

    public function labels()
    {
        return [
            self::ID_DRAW => "ドロー",
            self::ID_END => "ゲーム終了",
        ];
    }
}
