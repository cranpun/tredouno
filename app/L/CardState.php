<?php

namespace App\L;

class CardState extends ZzzLabel
{
    const ID_DECK = -3;
    const ID_PLACE = -2;
    const ID_HEAD = -1;

    public function labels()
    {
        return [
            self::ID_DECK => "山札",
            self::ID_PLACE => "捨て札",
            self::ID_HEAD => "先頭札",
        ];
    }
}
