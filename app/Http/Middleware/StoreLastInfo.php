<?php

namespace App\Http\Middleware;

use Closure;

class StoreLastInfo
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ret = $next($request);
        $user = \App\Models\User::user();
        if($user != null) {
            // 時間とrequest先を保存
            $data = ["last_datetime" => \Carbon\Carbon::now(),
                        "last_action" => $request->path(),];
            \Illuminate\Support\Facades\DB::table("user")
                ->where("id", $user["id"])
                ->update($data);
        }
        return $ret;
    }
}
