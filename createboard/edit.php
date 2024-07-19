<?php
require('/var/www/util.php');
require('/var/www/browser.php');

if(!isset($_POST["board-uuid"])){
    echo "ボードのUUIDが未入力です。(.../boards/board.php?uuid=[この部分]";
    exit;
}

$board = BoardTable::where('boarduuid', $_POST['board-uuid'])
    ->where('isdeleted', 0)
    ->first();

if(!$board){
    echo "板が見つかりません! UUIDが正しいか確認してください。";
    exit;
}

if($board->boardpassword != hash("sha256",$_POST["password"])){
    echo "パスワードが違います!";
    exit;
}

if(!isset($_POST["board-name"]) || !isset($_POST["board-description"]) || !isset($_POST["password"])){
    echo "板名 または、板の説明、またはパスワードが入力されていません。";
    exit;
}


$boardname = htmlspecialchars($_POST["board-name"], ENT_QUOTES, 'UTF-8');
$boarddescription = htmlspecialchars($_POST["board-description"], ENT_QUOTES, 'UTF-8');

if(isset($_POST["new-password"]) && $_POST["new-password"] != ""){
    $board->boardpassword = hash("sha256",$_POST["new-password"]);
}

$board->boardname = $boardname;
$board->boarddescription = $boarddescription;
$board->browser = getBrowser();
$board->save();

echo "更新しました";

?>