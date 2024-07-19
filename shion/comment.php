<?php

require('/var/www/util.php');
require('/var/www/browser.php');


if(!isset($_POST["thread-uuid"])){
    echo "スレッドが見つかりません。";
    exit;
}

$thread = ThreadTable::where('threaduuid', $_POST["thread-uuid"])->where('isdeleted', 0)->first();

if(!$thread){
    echo "スレッドが見つかりません。";
    exit;
}

if($thread->readonly == 1){
    echo "スレッドが書き込みできない状態です。";
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

$count = CommentTable::where('threaduuid', $thread->threaduuid)->count() + 1;

if($count > 1001){
    $thread->readonly = 1;
    $thread->save();
    echo "書き込み数上限を超えました。";
    exit;
}


$nickname = htmlspecialchars($_POST["nickname"], ENT_QUOTES, 'UTF-8');
$context = htmlspecialchars($_POST["comment"], ENT_QUOTES, 'UTF-8');

$comment = new CommentTable();
$comment->threaduuid = $thread->threaduuid;
$comment->commentuuid = bin2hex(random_bytes(16));
$comment->isadmin = $user->isadmin;
$comment->useruuid = $user->useruuid;
$comment->nickname = $nickname;
$comment->context = $context;
$comment->browser = getBrowser();
$comment->save();


$thread->last_comment = $comment->created_at;
$thread->save();

echo "書き込みに成功しました。 <a href='https://shion.free2ch.net?uuid=" . $thread->threaduuid . "'>スレッドに戻る</a>";

?>