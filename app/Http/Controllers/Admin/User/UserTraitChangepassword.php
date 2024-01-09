<?php
namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;

trait UserTraitChangepassword
{
    public function changepassword(Request $request)
    {
        $user = \App\Models\User::user();
        $validate = [
            "password"=> "required|min:8"
        ];
        $data = [
            "password" => $request->input("password")
        ];
        \Validator::make($data, $validate)->validate(); // throw exception

        $row = \App\Models\User::find($user->id);

        try {
            \DB::transaction(function () use ($data, $row) {
                \App\U\U::save(function () use ($data, $row) {
                    $row->setData($data);
                    $row->save();
                    return $row;
                }, "更新に失敗しました。");
            });
            return redirect()->route(\App\Models\User::user()->pr("-home"))->with("message-success", "パスワードを更新しました。");
        } catch (\Exception $e) {
            return back()->with("message-error", $e->getMessage())->withInput();
        }
    }

    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}