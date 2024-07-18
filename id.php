<?php
require_once('/var/www/util.php');

ini_set('session.cookie_domain', '.free2ch.net' );

session_start();


function randomStr($length = 12) {
    return substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $length);
}

function timestamp($a){
    return date('Y-m-d H:i:s', $a);
}

function newSessionOfAdmin($useruuid){
    $userkey = randomStr(64);
    $_SESSION["userkey"] = $userkey;
    $user = new VerifyTable();
    $user->useruuid = $useruuid;
    $user->userkey = $userkey;
    $user->isadmin = 1;
    $user->userexpiry = timestamp(strtotime('tomorrow midnight'));
    $user->save();
}

function newSession(){
    $useruuid = randomStr(12);
    $userkey = randomStr(64);
    $_SESSION["userkey"] = $userkey;
    $user = new VerifyTable();
    $user->useruuid = $useruuid;
    $user->userkey = $userkey;
    $user->userexpiry = timestamp(strtotime('tomorrow midnight'));
    $user->save();
}

if(!isset($_SESSION["userkey"])){
    newSession();
}else {
    $key = $_SESSION["userkey"];
    $user = VerifyTable::where('userkey', $key)->first();
    if($user){
        $diff = strtotime($user->userexpiry) - strtotime('now');
        if($diff <= 0){
            newSession();
        }
    }else {
        newSession();
    }
}

//$userfind = VerifyTable::where('userkey', $_SESSION["userkey"])->first();
//if($userfind){
//    echo "loggedin.<br>";
//    echo $userfind->userkey . "<br>";
//    echo $userfind->useruuid . "<br>";
//    echo $userfind->userexpiry . "<br>";
//}else {
//    echo "somehow not loggedin";
//}



?>