<?php
namespace cloudland\common\model;


use think\Model;

class User extends Model {

    protected $table = "users";

    protected $autoWriteTimestamp = true;
}