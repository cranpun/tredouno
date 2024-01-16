<?php

namespace App\L;

class CardEvent extends ZzzLabel
{
    const ID_DRAW2 = "draw2";
    const ID_WILD4 = "wild4";
    const ID_WILD = "wild";
    const ID_COLOR_WILD = "colorwild";
    const ID_COLOR_WILD4 = "colorwild4";
    const ID_END = "end";
    const ID_AFTERPULL = "afterpull";

    public function labels()
    {
        return [
            self::ID_DRAW2 => "ドロー2",
            self::ID_WILD => "ワイルド",
            self::ID_WILD4 => "ワイルド4",
            self::ID_COLOR_WILD => "色選択：ワイルド",
            self::ID_COLOR_WILD4 => "色選択：ワイルド4",
            self::ID_END => "ゲーム終了",
            self::ID_AFTERPULL => "カード引いた後",
        ];
    }
}
