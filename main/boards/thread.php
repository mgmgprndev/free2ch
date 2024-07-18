<?php

require('/var/www/util.php');


if(!isset($_POST["board-uuid"])){
    echo "板UUIDが不正です。";
    exit;
}

$board = BoardTable::where('boarduuid', $_POST["board-uuid"])->where('isdeleted', 0)->first();

if(!$board){
    echo "板が見つかりません。";
    exit;
}


if(!isset($_POST["thread-name"])){
    echo "スレッド名が入力されていません。";
    exit;
}

if(!isset($_POST["nickname"]) || !isset($_POST["comment"])){
    echo "ニックネームかコメントが入力されていません。";
    exit;
}

require('/var/www/id.php');

if(!$_SESSION["userkey"]){
    echo "セッションが無効です。";
    exit;
}

$user = VerifyTable::where('userkey', $_SESSION["userkey"])->first();
if(!$user){
    echo "セッションが無効です。";
    exit;
}

$threaduuid = bin2hex(random_bytes(16));
$threadname = htmlspecialchars($_POST["thread-name"], ENT_QUOTES, 'UTF-8');

$nickname = htmlspecialchars($_POST["nickname"], ENT_QUOTES, 'UTF-8');
$context = htmlspecialchars($_POST["comment"], ENT_QUOTES, 'UTF-8');


require('/var/www/browser.php');
$browser = getBrowser();

$thread = new ThreadTable();
$thread->boarduuid = $board->boarduuid;
$thread->threaduuid = $threaduuid;
$thread->threadname = $threadname;
$thread->browser = $browser;
$thread->save();

$comment = new CommentTable();
$comment->threaduuid = $threaduuid;
$comment->commentuuid = bin2hex(random_bytes(16));
$comment->isadmin = $user->isadmin;
$comment->useruuid = $user->useruuid;
$comment->nickname = $nickname;
$comment->context = $context;
$comment->browser = $browser;
$comment->save();


echo "スレッドが作成されました。 <a href='https://shion.free2ch.net?uuid=" . $threaduuid . "'>スレッドに行く</a>";

?>