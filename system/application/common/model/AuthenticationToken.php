<?php
namespace app\common\model;


use think\Model;

class AuthenticationToken extends Model {

    protected $table = "tokens";

    protected $autoWriteTimestamp = true;

    public function user(){
        return $this->belongsTo("User", "userId");
    }

    public function getExpiredAttr(){
        $upd = $this->update_time;
        if(time() > $upd + (60*60*24*7)) {
            return true;
        } else {
            return false;
        }
    }
}