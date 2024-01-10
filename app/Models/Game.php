<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $table = "game";
    protected $guarded = [
        "id",
    ];
    const CARD_PREFIX = "cd_";
    
    public $players;

    public static function validaterule(): array
    {
        $ret = [
            "playing" => "integer|required",
            "order" => "string|required",
        ];
        foreach (self::cardNames() as $cn) {
            $ret[$cn] = "integer|required";
        }
        return $ret;
    }

    public static function cardNames(): array
    {
        // 以下、カードの種類。
        // -3 : 山札
        // -2 : 捨て札
        // -1 : 先頭札
        // https://mattel.co.jp/wp-content/uploads/2022/07/uno_minimalista.pdf

        $ret = [];
        $p = self::CARD_PREFIX;

        foreach (["r", "g", "b", "y"] as $clr) {
            // // 数字、色ごとに。
            // 1から9
            for ($i = 1; $i <= 9; $i++) {
                // 1～9は2枚ずつ
                $ret[] = "{$p}{$clr}_num0{$i}_1";
                $ret[] = "{$p}{$clr}_num0{$i}_2";
            }
            // // 数字0は1枚だけ
            $ret[] = "{$p}{$clr}_num00_1";

            // 文字カードも2枚ずつ
            foreach ([
                "draw2",
                "reverse",
                "skip",
            ] as $chr) {
                $ret[] = "{$p}{$clr}_{$chr}_1";
                $ret[] = "{$p}{$clr}_{$chr}_2";
            }
        }

        // ワイルドカードは色関係なく8枚
        for ($i = 1; $i <= 8; $i++) {
            $ret[] = "{$p}whild_{$i}";
        }

        // ワイルドドロー4カードは色関係なく4枚
        for ($i = 1; $i <= 4; $i++) {
            $ret[] = "{$p}whild4_{$i}";
        }

        return $ret;
    }

    public function loadPlayers()
    {
        $user_ids = explode(",", $this->order);
        $players = \App\Models\User::whereIn("user.id", $user_ids)->get();
        // 並び順を調整
        $this->players = [];
        foreach($user_ids as $user_id) {
            foreach($players as $player) {
                if($player->id == $user_id) {
                    $this->players[] = $player;
                    break;
                }
            }
        }
    }

    public function addOrder($user_id)
    {
        $orders = explode(",", $this->order);
        if(!in_array($user_id, $orders)) {
            $orders[] = $user_id;
            $this->order = join(",", $orders);
        }
    }

    public function setData(array $data)
    {
    }
}
