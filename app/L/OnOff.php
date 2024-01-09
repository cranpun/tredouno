<?php

namespace App\L;

class OnOff extends ZzzLabel
{
    const ID_OFF = "off";
    const ID_ON = "on";

    private string $labelOn;
    private string $labelOff;

    public function __construct(string $labelOn = "◯", string $labelOff = "－")
    {
        $this->labelOn = $labelOn;
        $this->labelOff = $labelOff;
    }

    public function labels()
    {
        return [
            self::ID_OFF => $this->labelOff,
            self::ID_ON => $this->labelOn,
        ];
    }

    public static function isOn($val)
    {
        return $val == self::ID_ON;
    }
    public static function isOff($val)
    {
        return $val == self::ID_OFF;
    }
}
