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

    public $players;
    public $turninfo;

    public const DEAL_CARD_COUNT = 7;

    public static function validaterule(): array
    {
        $ret = [
            "playing" => "integer|required",
            "order" => "string|required",
        ];
        foreach (\App\S\CardName::cardNames() as $cn) {
            $ret[$cn] = "integer|required";
        }
        return $ret;
    }

    public function init()
    {
        // 全カードを山に
        $names = \App\S\CardName::cardNames();
        foreach ($names as $name) {
            $this->{$name} = \App\L\CardState::ID_DECK;
        }

        // 各種パラメータを初期化
        $this->playing = \App\L\OnOff::ID_OFF;
        $this->last_event_at = now();
        $this->cardevent = null;
        $this->eventdata = null;
    }

    public function getCards()
    {
        $ret = [];
        $arr = $this->toArray();
        foreach (\App\S\CardName::cardnames() as $cname) {
            // 状態の指定があれば、それのみ。なければ全て。
            $ret[$cname] = $arr[$cname];
        }
        return $ret;
    }

    public function getCardsByStatus($cardStatus)
    {
        $ret = [];
        $arr = $this->toArray();
        foreach (\App\S\CardName::cardNames() as $cname) {
            if ($arr[$cname] == $cardStatus) {
                // 状態の指定があれば、それのみ。なければ全て。
                $ret[] = $cname;
            }
        }
        return $ret;
    }

    public function getHeadCard()
    {
        $cards = $this->getCardsByStatus(\App\L\CardState::ID_HEAD);
        if (count($cards) > 0) {
            return $cards[0];
        } else {
            return null;
        }
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

    public function deal($user_id, $count)
    {
        $deck = $this->getCardsByStatus(\App\L\CardState::ID_DECK);
        $place = $this->getCardsByStatus(\App\L\CardState::ID_PLACE);
        if (count($deck) + count($place) <= 0) {
            return;
        } else if (count($deck) >= $count) {
            // 山の枚数が引く数より多ければ普通に全部引く
            $cards = \App\L\CardState::dealCard($deck, $count);
        } else {
            // 足りない場合はまず山札を全部引いて、残りは捨て札を山札に残してから改めて引く
            // // 捨て札を山札に戻す
            foreach ($place as $p) {
                $this->{$p} = \App\L\CardState::ID_DECK;
            }

            // // 山札全部と残りを捨て札から引く
            $cards = array_merge($deck, \App\L\CardState::dealCard($deck, $count - count($deck)));
        }

        // 配られたカードを指定したユーザの手札に
        foreach ($cards as $c) {
            $this->{$c} = $user_id;
        }

        return $cards;
    }

    public function orderArr()
    {
        return explode(",", $this->order);
    }

    public function nextOrder()
    {
        $odr = $this->orderArr();
        $last = array_shift($odr);
        $odr[] = $last; // last == 今のuser_id
        $this->order = join(",", $odr);
    }

    public function addOrder($user_id)
    {
        if ($this->order) {
            // 既にプレイヤーがいれば、重複チェックしつつ追加
            $orders = $this->orderArr();
            if (!in_array($user_id, $orders)) {
                $orders[] = $user_id;
                $this->order = join(",", $orders);
            }
        } else {
            // 初プレイヤーであればそのまま登録
            $this->order = $user_id;
        }
    }

    public function isPlaying(): bool
    {
        return $this->playing == \App\L\OnOff::ID_ON;
    }

    public function isTurn($user_id): bool
    {
        $players = explode(",", $this->order);
        if (count($players) <= 0) {
            // 異常だけどとりあえず
            return false;
        } else {
            $turn_id = $players[0];
            return $turn_id == $user_id;
        }
    }

    public function setCardEvent($event, $data)
    {
        $this->cardevent = $event;
        $this->eventdata = $data;
    }

    public function setData(array $data)
    {
        $this->playing = \App\U\U::getd($data, "playing", $this->playing);
        $this->last_event_at = \App\U\U::getd($data, "last_evet_at", $this->last_event_at);
        $this->order = \App\U\U::getd($data, "order", $this->order);

        // カードの状態
        foreach (\App\S\CardName::cardNames() as $card) {
            $this->{$card} = \App\U\U::getd($data, $card, $this->{$card});
        }
    }
}
