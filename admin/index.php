<?php
require( "/var/www/util.php");
require( "/var/www/id.php");

if(!isset($_GET["confirm"])){
    echo "不正です";
    exit;
}

if($_GET["confirm"] != "YES"){
    echo "不正です。";
    exit;
}

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
    try {
        $threads = ThreadTable::where("boarduuid", $uuid)->get();

        foreach($threads as $t){
            $comments = CommentTable::where('threaduuid', $t->threaduuid)->get();

            foreach($comments as $comment){
                $comment->isdeleted = 1;
                $comment->save();
            }

            $t->isdeleted = 1;
            $t->save();
        }

        $board = BoardTable::where('boarduuid', $_GET['uuid'])->first();
        $board->isdeleted = 1;
        $board->save();

        echo "板の削除に成功しました。";
    } catch (Exception $e) {
        echo "エラーがー発生しました。" . $e->getMessage();
    }
}

if($target == "thread"){
    try {
        $comments = CommentTable::where('threaduuid', $uuid)->get();
        foreach($comments as $comment){
            $comment->isdeleted = 1;
            $comment->save();
        }
        
        $thread = ThreadTable::where('threaduuid', $uuid)->first();
        $thread->isdeleted = 1;
        $thread->save();

        echo "スレッドの削除に成功しました。";
    } catch (Exception $e) {
        echo "エラーがー発生しました。" . $e->getMessage();
    }
}

if($target == "comment"){
    try {
        $comment = CommentTable::where('commentuuid', $uuid)->first();
        $comment->isdeleted = 1;
        $comment->save();
        
        echo "コメントの削除に成功しました。";
    } catch (Exception $e) {
        echo "エラーがー発生しました。" . $e->getMessage();
    }
}

?>