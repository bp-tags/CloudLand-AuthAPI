<?php


namespace cloudland\auth\controller;

use cloudland\common\Helper;
use cloudland\common\model\AuthenticationToken;
use cloudland\common\model\JoinServer;
use think\Controller;

class Join extends Controller {
    /**
     * Join a server, should be fired by client
     * @return \think\response\Json
     */
    public function now(){
        if(!isset($_GET["clientKey"]) or !isset($_GET["random"])) {
            return Helper::json(false, "Invalid data");
        }

        $ckey = trim($_GET["clientKey"]);
        $random = trim($_GET["random"]);

        $token = AuthenticationToken::get(["clientKey" => $ckey]);
        if(!$token) {
            return Helper::json(false, "Not authenticated! ");
        }
        if($token->expired) {
            return Helper::json(false, "Token expired! ");
        }

        $j = JoinServer::create([
            "clientKey" => $ckey,
            "uuid" => $token->user->uuid,
            "userId" => $token->user->id,
            "random" => $random
        ]);

        return Helper::json(true);
    }

    /**
     * Server check the random
     * @return \think\response\Json
     */
    public function check(){
        if(!isset($_GET["uuid"]) or !isset($_GET["random"])) {
            return Helper::json(false, "Invalid data");
        }

        $uuid = trim($_GET["uuid"]);
        $random = trim($_GET["random"]);

        $j = JoinServer::get(["uuid" => $uuid, "random" => $random]);

        if(!$j) {
            return Helper::json(false, "Invalid credentials");
        }

        if(time() > $j->create_time + (60*10)) {
            return Helper::json(false, "Join server timed out");
        }

        // collect data
        $user = $j->user;
        $data = [
            "username" => $user->username,
            "uuid" => $user->uuid,
            // "skin" => $user->skinName
        ];

        $j->delete(); // finished so we delete this

        return Helper::json(true, "Valid credentials", $data);
    }
}