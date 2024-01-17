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
                $card = new \App\S\CardName($cardname);


                // 一応出せるカードか確認
                if (!\App\S\CardName::canPutCard($cardname, $head, $game->cardevent, $game->eventdata)) {
                    return $ret->with("message-error", "出せるカードではありませんでした。");
                }

                // 一応、自分のカードか確認
                if (!in_array($cardname, $hands)) {
                    return $ret->with("message-error", "手持ちのカードではありませんでした。");
                }

                // cardeventの設定。else以外ではもう一回出した人の手番。
                if (count($hands) == 1) {
                    //  最後の1枚を出したのでゲームおしまい。eventに終了フラグを設定
                    $game->playing = \App\L\OnOff::ID_OFF;
                    $game->cardevent = \App\L\CardEvent::ID_END;
                    // readyにリダイレクト
                    $ret = redirect()->route($user->pr("-game-ready"), ['game_id' => $game->id]);
                } else if ($card->kind == "wild") {
                    // wild
                    $game->setCardEvent(\App\L\CardEvent::ID_COLOR_WILD, null);
                    $ret->with("message-success", "色を選択してください。");
                } else if ($card->kind == "wild4") {
                    // wild4
                    // チャレンジのために現在の状態をeventdataに設定
                    $json = json_encode([
                        "head" => $head,
                        "cardevent" => $game->cardevent,
                        "eventdata" => $game->eventdata,
                    ]);
                    $game->setCardEvent(\App\L\CardEvent::ID_COLOR_WILD4, $json);
                    $ret->with("message-success", "色を選択してください。またこのあと次の人がチャレンジします。成功した場合カードが増えます。");
                } else {

                    // ここから先は順番変更を含む処理
                    if ($card->kind == "draw2") {
                        $game->setCardEvent(\App\L\CardEvent::ID_DRAW2, null);
                    } else {
                        // その他のカード
                        $game->setCardEvent(null, null); // 通常のターンになるので、リセット。
                    }

                    // 順番に関する操作
                    $order = $game->orderArr();
                    if (($card->kind == "skip") // skipか
                        || $card->kind == "reverse" && count($order) == 2
                    ) // 二人の時のreverse
                    {
                        // 次の次の人。
                        $game->nextOrder();
                        $game->nextOrder();
                    } else if ($card->kind == "reverse") {
                        // 3人以上のリバースは並び順を逆に
                        $game->order = join(",", array_reverse($order));
                    } else {
                        // // それ以外は単純に次の人
                        $game->nextOrder();
                    }
                }

                // 今の先頭札を捨て札に
                $game->{$head} = \App\L\CardState::ID_PLACE;

                // 今回のカードを先頭札に（＝自分の手札から消える）
                $game->{$cardname} = \App\L\CardState::ID_HEAD;
                $head = $game->getHeadCard(); // 変数値更新

                $game = \DB::transaction(function () use ($game) {
                    $game = \App\U\U::save(function () use ($game) {
                        $game->save();
                        return $game;
                    }, "カードを出すのに失敗しました。");
                    return $game;
                });
            } catch (\Exception $e) {
                \Log::error($e);
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
