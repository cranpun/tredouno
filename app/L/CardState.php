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

    public static function dealCard($cnames, $count)
    {
        $ret = [];
        // 指定されたIDに、指定回数カードを配る
        for ($i = 0; $i < $count; $i++) {
            $maxCname = count($cnames) - 1;
            $deal = random_int(0, $maxCname);
            $ret[] = $cnames[$deal];
            array_splice($cnames, $deal, 1); // このカードは配ったので対象外
        }
        return $ret;
    }
}
