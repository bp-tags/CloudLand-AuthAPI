<?php


namespace cloudland\common;


class Helper {
    public static function json($ok, $message, $data = []) {
        $status = null;
        if($ok === true) {
            $status = "ok";
        }  else if($ok === false) {
            $status = "error";
        } else {
            $status = $ok;
        }
        return array_merge(["status" => $status, "message" => $message], $data);
    }
}