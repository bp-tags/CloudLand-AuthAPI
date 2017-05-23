<?php


namespace cloudland\common\model;


use think\Model;

class JoinServer extends Model {

    protected $table = "joins";

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    public function token(){
        return $this->belongsTo("AuthenticationToken", "tokenId");
    }
}