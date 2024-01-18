<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitChallenge
{
    public function challenge(\Illuminate\Http\Request $request, $game_id, $value)
    {
        $game = \App\Models\Game::find($game_id);
        $user = \App\Models\User::user();
        $ret = redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $game->id]);

        if ($game->isPlaying()) {
            if ($game->isTurn($user->id)) {
                try {
                    $game->last_event_at = now();
                    $eventdata = json_decode($game->eventdata);

                    // チャレンジに関する処理
                    if ($value == \App\L\OnOff::ID_OFF) {
                        $game->deal($user->id,  4);
                    } else {
                        // チャレンジして、結果に応じて対応。
                        // 前の人はorderの一番末尾
                        $order = $game->orderArr();
                        $preuser_id = array_pop($order);
                        $preuser = \App\Models\User::find($preuser_id);
                        $eventdata = json_decode($game->eventdata);
                        if($this->challenge_canPut($game, $preuser, $eventdata)) {
                            // チャレンジ成功。preuserに4枚追加
                            $game->deal($preuser->id, 4);
                            $ret->with("message-success", "チャレンジ成功。{$preuser->display_name}に4枚追加しました。");
                        } else {
                            // チャレンジ失敗。現userに6枚
                            $game->deal($user->id, 6);
                            $ret->with("message-success", "チャレンジ失敗。6枚追加しました。");
                        }
                    }

                    // イベントを通常wildに変更。eventdataに選択された色が入っているのでそのまま利用。
                    $game->setCardEvent(\App\L\CardEvent::ID_WILD, $eventdata->color);

                    // 順番を次の人へ
                    $game->nextOrder();

                    $game = \DB::transaction(function () use ($game, $user) {
                        $game = \App\U\U::save(function () use ($game, $user) {
                            $game->save();
                            return $game;
                        }, "チャレンジの通信に失敗しました。");
                        return $game;
                    });
                } catch (\Exception $e) {
                    \Log::error($e);
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
    private function challenge_canPut($game, $preuser, $eventdata): bool
    {
        $cards = $game->getCardsByStatus($preuser->id);
        foreach($cards as $c) {
            if(\App\S\CardName::canPutCard($c, $eventdata->head, $eventdata->cardevent, $eventdata->eventdata)) {
                return true;
            }
        }
        // 1枚も出せるものがなかった
        return false;
    }
}
