<?php
require( "/var/www/util.php");
require( "/var/www/id.php");

$user = VerifyTable::where('userkey', $_SESSION["userkey"])->first();

if(!$user){
    echo "セッションが無効です。";
    exit;
}

if($user->isadmin == 0){
    echo "あなたは管理者権限を持っていません。";
    exit;
}

$target = isset($_GET["target"]) ? $_GET["target"] : "";
$uuid = isset($_GET["uuid"]) ? $_GET["uuid"] : "";

if($target == "" || !( $target == "board" || $target == "thread" || $target == "comment" ) ){
    echo "無効なターゲットです。 有効な例: board, thread, comment";
    exit;
}

if($uuid == ""){
    echo "UUIDを指定してください。";
    exit;
}

if($target == "board"){
    $board = BoardTable::where('boarduuid', $uuid)->first();
    if($board){
        echo $board->browser;
    }else {
        echo "見つかりませんでした。";
    }
}

if($target == "thread"){
    $thread = ThreadTable::where('threaduuid', $uuid)->first();
    if($thread){
        echo $thread->browser;
    }else {
        echo "見つかりませんでした。";
    }
}

if($target == "comment"){
    $comment = CommentTable::where('commentuuid', $uuid)->first();
    if($comment){
        echo $comment->browser;
    }else {
        echo "見つかりませんでした。";
    }
}

?>

<script>
document.body.innerHTML = document.body.innerHTML.replace("-","<br>");
</script>