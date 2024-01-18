<?php

/**
 * @param $cardname
 */

$card = new \App\S\CardName($cardname);
?>

<span id="{{ $cardname }}" style="background-color: {{ \App\S\CardName::colorValue($card->color) }}; color: white; width: 56px; height: 87px; text-align: center; display: inline-block; border-radius: 10px;">
<strong><b style="display: inline-block; width: 45px; text-align: center; font-size: 30px; padding-top: 20px;">
    {{ \App\S\CardName::kindLabel($card->kind) }}
</b></strong>
</span>