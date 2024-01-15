<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitPullcard
{
    public function pullcard(\Illuminate\Http\Request $request, $game_id)
    {
        $game = \App\Models\Game::find($game_id);
        $user = \App\Models\User::user();
        $ret = redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $game->id]);

        if ($game->isPlaying()) {
            if ($game->isTurn($user->id)) {
                try {
                    $game->last_event_at = now();
                    $decks = $game->getCardsByStatus(\App\L\CardState::ID_DECK);
                    if (count($decks) <= 0) {
                        // 山札がないので、捨て札を山札に戻す。その時、それらのカードが山札になるので変数上書き
                        $decks = $game->getCardsByStatus(\App\L\CardState::ID_PLACE);
                        $data = [];
                        foreach ($decks as $p) {
                            $data[$p] = \App\L\CardState::ID_DECK;
                        }
                    }
                    $pullname = \App\L\CardState::dealCard($decks, 1);
                    $game->{$pullname[0]} = $user->id;

                    // 出せるカードだったら出す。
                    // MYTODO 要検討。wildのときにpullしたら上書きしちゃう。eventdataか何かに退避させて復帰するつくりをしないと。
                    if (\App\S\CardName::canPutCard($pullname[0], $game->getHeadCard(), $game->cardevent, $game->eventdata)) {
                        $game->setCardEvent(\App\L\CardEvent::ID_AFTERPULL, null);
                        $ret->with("message-success", "出せるカードを引きました。出すのであれば選択してください。そのままであればパスを押してください。");
                    } else {
                        // 順番を次の人へ
                        $order = $game->orderArr();
                        array_splice($order, 0, 1);
                        $order[] = $user->id;
                        $game->order = join(",", $order);
                    }

                    $game = \DB::transaction(function () use ($game, $user) {
                        $game = \App\U\U::save(function () use ($game, $user) {
                            $game->save();
                            return $game;
                        }, "カードに引くのを失敗しました。");
                        return $game;
                    });
                } catch (\Exception $e) {
                    return back()->with("message-error", $e->getMessage())->withInput();
                }
            }
        }

        // 例外が発生しなければ正常に移動
        return $ret;
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
