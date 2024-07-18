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
    try {
        $threads = ThreadTable::where("boarduuid", $uuid)->get();
        foreach($threads as $t){
            CommentTable::where('threaduuid', $t->threaduuid)->delete();
            $t->delete();
        }
        BoardTable::where('boarduuid', $_GET['uuid'])->delete();   
        echo "板の削除に成功しました。";
    } catch (Exception $e) {
        echo "エラーがー発生しました。" . $e->getMessage();
    }
}

if($target == "thread"){
    try {
        CommentTable::where('threaduuid', $uuid)->delete();
        ThreadTable::where('threaduuid', $uuid)->delete();
        echo "スレッドの削除に成功しました。";
    } catch (Exception $e) {
        echo "エラーがー発生しました。" . $e->getMessage();
    }
}

if($target == "comment"){
    try {
        $comment = CommentTable::where('commentuuid', $uuid)->first();
        $comment->useruuid="<span style='color:red;font-weight:bold;'>[deleted]</span>";
        $comment->nickname="<span style='color:red;font-weight:bold;'>[deleted]</span>";
        $comment->context="<span style='color:red;font-weight:bold;'>[deleted]</span>";
        $comment->isadmin=0;
        $comment->save();
        
        echo "コメントの削除に成功しました。";
    } catch (Exception $e) {
        echo "エラーがー発生しました。" . $e->getMessage();
    }
}

?>