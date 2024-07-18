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
?>

<h1>本当に削除してよろしいですか?</h1>
<p>
    <b>TARGET:</b> <?php echo $target; ?><br>
    <b>UUID:</b> <?php echo $uuid; ?><br>
</p>
<input style="width:500px; height:25px;" placeholder="「はい」なら「YES CONFIRM」と入力" oninput="document.getElementById('btn').style.display = this.value == 'YES CONFIRM' ? '' : 'none';">
<div id="btn" style="padding: 16px; background-color:red;display: none;"><p><a href="/?target=<?php echo $target; ?>&uuid=<?php echo $uuid; ?>">削除を確定</a></p></div>