<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitPlaystore
{
    public function playstore(\Illuminate\Http\Request $request, $game_id)
    {
        $game = \App\Models\Game::find($game_id);
        if (!$game->isPlaying()) {
            try {
                $game = \DB::transaction(function () use ($game) {
                    $game = \App\U\U::save(function () use ($game) {
                        $game->init();
                        $game->last_event_at = now();
                        $game->playing = \App\L\OnOff::ID_ON;
                        $game = $this->playstore_dealcard($game);

                        // MYTODO プレイ順をシャッフル。開発時は無効。
                        // $odr = $game->orderArr();
                        // shuffle($odr);
                        // $game->order = join(",", $odr);

                        $game->save();
                        return $game;
                    }, "入室に失敗しました。");
                    return $game;
                });
            } catch (\Exception $e) {
                return back()->with("message-error", $e->getMessage())->withInput();
            }
        }

        // 例外が発生しなければ正常に移動
        return redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $game->id]);
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************

    private function playstore_dealcard(\App\Models\Game $game)
    {
        // 本来のルールとは違うけど、最初に先頭札を決める。ランダムで数字出すののやり直しを防ぐため。
        $cnames = \App\S\CardName::cardNamesOnlyNum();
        $head = \App\L\CardState::dealCard($cnames, 1);
        $game->{$head[0]} = \App\L\CardState::ID_HEAD;

        // 参加者全員にカードを配る
        foreach (explode(",", $game->order) as $player_id) {
            // 現在の山札を取得
            $cnames = $game->getCardsByStatus(\App\L\CardState::ID_DECK);
            $cards = \App\L\CardState::dealCard($cnames, \App\Models\Game::DEAL_CARD_COUNT);
            $data = [];
            // データセット用のデータを作成
            foreach ($cards as $card) {
                $data[$card] = $player_id;
            }
            $game->setData($data);
        }

        return $game;
    }
}
