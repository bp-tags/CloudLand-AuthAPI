<?php


namespace cloudland\auth\controller;


use cloudland\common\Helper;
use cloudland\common\model\AuthenticationToken;
use cloudland\common\model\User;
use think\Controller;
use think\response\Json;
use think\Validate;

class Account extends Controller {
    /**
     * Login as a player and generate a new client key.
     * @return Json
     */
    public function login() {
        if(!Validate::make([
            "clientId" => "require",
            "email" => "require|email",
            "password" => "require"
        ])->check($_GET)) {
            return new Json(["status" => "error", "message" => "Invalid data"]);
        }

        $cid = trim($_GET["clientId"]);
        $email = trim($_GET["email"]);
        $password = md5(trim($_GET["password"]));

        $user = User::get(["email" => $email, "password" => $password]);
        if(!$user) {
            return Helper::json(false, "Invalid credentials! ");
        }

        // two parts: SHA1 + MD5
        $ckey = sha1("CLOUDLAND-" . time() . "-" . md5($user->email) . "-" . md5($user->password)) . md5(time());

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

        return Helper::json(true, "success", ["clientKey" => $ckey]);
    }

    /**
     * Refresh a token.
     * @return Json
     */
    public function refresh(){
        if(!Validate::make([
            "clientId" => "require",
            "clientKey" => "require"
        ])->check($_GET)) {
            return Helper::json(false, "Invalid data");
        }

        $cid = trim($_GET["clientId"]);
        $ckey = trim($_GET["clientKey"]);

        $token = AuthenticationToken::get(["clientId" => $cid, "clientKey" => $ckey]);
        if(!$token) {
            return Helper::json(false, "Invalid credentials! ");
        }
        if($token->expired) {
            return Helper::json(false, "Token expired, please re-login. ");
        }

        $user = $token->user;
        $ckey = sha1("CLOUDLAND-" . time() . "-" . md5($user->email) . "-" . md5($user->password)) . md5(time());
        $token->clientKey = $ckey;
        $token->save();
        return Helper::json(true, "success", ["clientKey" => $ckey]);
    }
}