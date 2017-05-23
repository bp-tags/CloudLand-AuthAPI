<?php
namespace cloudland\common;


use cloudland\common\model\AuthenticationToken;
use think\Controller;
use think\response\Json;

class UserController extends Controller {

    protected $user = null;

    public function _initialize() {
        $error = false;
        if(!isset($_GET["u"]) || !isset($_GET["c"]) || !isset($_GET["k"])) {
            $error = true;
        } else {
            // User Id
            $u = intval($_GET["u"]);

            // Client Id
            $c = md5(trim($_GET["c"]));

            // Client key
            $k = md5(trim($_GET["k"]));

            $this->user = AuthenticationToken::get(["u" => $u, "c" => $c, "k" => $k]);
            if(!$this->user) {
                $error = true;
            }
        }

        if($error) {
            return new Json(["status" => "error", "message" => "Not authenticated! "]);
        }
    }

}