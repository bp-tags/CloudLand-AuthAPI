<?php
namespace cloudland\common\model;


use think\Model;

class AuthenticationToken extends Model {

    protected $table = "tokens";

    protected $autoWriteTimestamp = true;

    public function user(){
        return $this->belongsTo("User", "userId");
    }

    public function getExpiredAttr(){

    }
}