<?php

namespace App\Http\Controllers\Admin\Game;

trait GameTraitCreatestore
{
    public function createstore(\Illuminate\Http\Request $request)
    {
        // 特にポストするデータはなし。
        // $data = $request->all();
        // $data["enroll_id"] = $enroll_id;
        // $val = \App\Models\Lesson::validaterule();
        // \Validator::make($data, $val)->validate(); // throw exception

        try {
            $row = \DB::transaction(function () {
                $row = \App\U\U::save(function () {
                    $user = \App\Models\User::user();
                    $row = new \App\Models\Game();
                    $row->order = $user->id;
                    $row->last_event_at = now();
                    $row->save();
                    return $row;
                }, "登録に失敗しました。");
                return $row;
            });
            $mes = "登録しました。";

            return redirect()->route(\App\Models\User::user()->pr("-game-play"), ['game_id' => $row->id]);
        } catch (\Exception $e) {
            return back()->with("message-error", $e->getMessage())->withInput();
        }
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}
