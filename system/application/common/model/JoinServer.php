<?php


namespace app\common\model;


use think\Model;

class JoinServer extends Model {

    protected $table = "joins";

    protected $autoWriteTimestamp = true;
    protected $updateTime = false;

    public function user(){
        return $this->belongsTo("User", "userId");
    }
}