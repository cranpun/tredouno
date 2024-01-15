<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitPutcard
{
    public function putcard(\Illuminate\Http\Request $request, $game_id, $cardname)
    {
        $game = \App\Models\Game::find($game_id);
        if ($game->isPlaying()) {
            try {
                $user = \App\Models\User::user();
                $ret = redirect()->route($user->pr("-game-play"), ['game_id' => $game->id]);
                $head = $game->getHeadCard();
                $hands = $game->getCardsByStatus($user->id);

                // MYTODO 最後の一枚が出せるカード（数字）か確認

                // 一応出せるカードか確認
                if (!\App\S\CardName::canPutCard($cardname, $head)) {
                    return $ret->with("message-error", "出せるカードではありませんでした。");
                }

                // 一応、自分のカードか確認
                if (!in_array($cardname, $hands)) {
                    return $ret->with("message-error", "手持ちのカードではありませんでした。");
                }

                // 今の先頭札を捨て札に
                $game->{$head} = \App\L\CardState::ID_PLACE;

                // 今回のカードを先頭札に（＝自分の手札から消える）
                $game->{$cardname} = \App\L\CardState::ID_HEAD;
                $head = $game->getHeadCard(); // 変数値更新

                // MYTODO カードがイベント札であればイベント情報を登録
                // ドロー2、ドロー4くらい？

                // 順番に関する操作
                // // MYTODO skipとリバース、はそのように
                // // それ以外は今の人
                $odr = $game->orderArr();
                $last = array_shift($odr);
                $odr[] = $last; // last == 今のuser_id
                $game->order = join(",", $odr);

                if (count($hands) == 1) {
                    //  最後の1枚を出したのでゲームおしまい。eventに終了フラグを設定
                    $game->playing = \App\L\OnOff::ID_OFF;
                    $game->cardevent = \App\L\CardEvent::ID_END;
                    // readyにリダイレクト
                    $ret = redirect()->route($user->pr("-game-ready"), ['game_id' => $game->id]);
                }

                $game = \DB::transaction(function () use ($game) {
                    $game = \App\U\U::save(function () use ($game) {
                        $game->save();
                        return $game;
                    }, "カードを出すのに失敗しました。");
                    return $game;
                });
            } catch (\Exception $e) {
                return back()->with("message-error", $e->getMessage())->withInput();
            }
        }

        // 例外が発生しなければ正常に移動
        return $ret;;
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
