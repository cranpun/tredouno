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

    public const DEAL_CARD_COUNT = 7;

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

    public function getCards()
    {
        $ret = [];
        $arr = $this->toArray();
        foreach (\App\Models\Game::cardnames() as $cname) {
            // 状態の指定があれば、それのみ。なければ全て。
            $ret[$cname] = $arr[$cname];
        }
        return $ret;
    }

    public function getCardsByStatus($cardStatus)
    {
        $ret = [];
        $arr = $this->toArray();
        foreach (\App\Models\Game::cardnames() as $cname) {
            if ($arr[$cname] == $cardStatus) {
                // 状態の指定があれば、それのみ。なければ全て。
                $ret[] = $cname;
            }
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
        foreach ($user_ids as $user_id) {
            foreach ($players as $player) {
                if ($player->id == $user_id) {
                    $this->players[] = $player;
                    break;
                }
            }
        }
    }

    public function addOrder($user_id)
    {
        if ($this->order) {
            // 既にプレイヤーがいれば、重複チェックしつつ追加
            $orders = explode(",", $this->order);
            if (!in_array($user_id, $orders)) {
                $orders[] = $user_id;
                $this->order = join(",", $orders);
            }
        } else {
            // 初プレイヤーであればそのまま登録
            $this->order = $user_id;
        }
    }

    public function isPlaying()
    {
        return $this->playing == \App\L\OnOff::ID_ON;
    }

    public function setData(array $data)
    {
        $this->playing = \App\U\U::getd($data, "playing", $this->playing);
        $this->last_event_at = \App\U\U::getd($data, "last_evet_at", $this->last_event_at);
        $this->order = \App\U\U::getd($data, "order", $this->order);

        // カードの状態
        foreach (\App\Models\Game::cardNames() as $card) {
            $this->{$card} = \App\U\U::getd($data, $card, $this->{$card});
        }
    }
}
