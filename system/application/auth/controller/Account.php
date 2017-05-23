<?php


namespace cloudland\auth\controller;


use cloudland\common\Helper;
use cloudland\common\model\AuthenticationToken;
use cloudland\common\model\User;
use think\Controller;
use think\response\Json;
use think\Validate;

class Account extends Controller {
    public function login() {
        if(!Validate::make([
            "c" => "require",
            "email" => "require|email",
            "password" => "require"
        ])->check($_GET)) {
            return new Json(["status" => "error", "message" => "Invalid data"]);
        }

        $cid = trim($_GET["c"]);
        $email = trim($_GET["email"]);
        $password = md5(trim($_GET["password"]));

        $user = User::get(["email" => $email, "password" => $password]);
        if(!$user) {
            return Helper::json(false, "Invalid credentials! ");
        }

        $ckey = "";

        $token = AuthenticationToken::get(["clientId" => $cid]);
        if(!$token) {
            $token = AuthenticationToken::create([
                "clientId" => $cid,
                "clientKey" => $ckey,
                "userId" => $user->id
            ]);
        } else {
            $token->clientKey = $ckey;
            $token->save();
        }
    }
}