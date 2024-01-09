<?php
namespace App\Http\Controllers\Pub\User;

trait UserTraitLogin
{
    public function login()
    {
        if(\App\Models\User::isLogin()) {
            // ログイン済みなのでリダイレクト
            $user = \App\Models\User::user();
            return redirect()->intended(route("{$user->role}-home"))->with("message-success", "ログイン済みのためホームに移動しました。");;;
        }
        return view("pub.user.login.main");
    }
    public function authenticate(\Illuminate\Http\Request $request)
    {
        $credentials = $request->only("name", "password");
        $res = \Auth::attempt($credentials);
        $data = $request->all();
        if($res) {
            // 有効なユーザか確認
            // $user = \App\Models\User::activeQuery()->where("name", "=", $data["name"])->first(); // error phpstan
            $user = \App\Models\User::whereNull("deleted_at")->where("name", "=", $data["name"])->first();
            if(!$user) {
                \Auth::logout();
                \U::invokeErrorValidate($request, "無効なユーザです。");
            }

            // ログイン成功
            return redirect()->intended(route("{$user->role}-home"));
        } else {
            // ログイン失敗のため、強制的にバリデーションエラーを発生。
            return redirect()->route('login')->withInput()->with("message-error", "ログインに失敗しました。アカウントとパスワードをもう一度ご確認ください。");
        }
    }


    // *************************************
    // utils : 衝突を避けるため、action名_メソッド名とすること
    // *************************************
}