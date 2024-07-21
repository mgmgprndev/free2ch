<?php

if(isset($_GET["posted"]) && $_GET["posted"] !="" ){
    echo urldecode(base64_decode($_GET["posted"]));
    exit;
}


require('/var/www/util.php');
require('/var/www/browser.php');

if(!isset($_POST["board-name"]) || !isset($_POST["board-description"]) || !isset($_POST["password"])){
    echo "板名 または、板の説明、またはパスワードが入力されていません。";
    exit;
}

$boarduuid = bin2hex(random_bytes(16));
$boardname = htmlspecialchars($_POST["board-name"], ENT_QUOTES, 'UTF-8');
$boarddescription = htmlspecialchars($_POST["board-description"], ENT_QUOTES, 'UTF-8');
$boardpassword = hash("sha256",$_POST["password"]);

$board = new BoardTable();
$board->boarduuid = $boarduuid;
$board->boardname = $boardname;
$board->boarddescription = $boarddescription;
$board->boardpassword = $boardpassword;
$board->browser = getBrowser();
$board->save();

echo "板が作成されました。<a href='https://free2ch.net/boards/board.php?uuid=" . $boarduuid . "'>板に行く</a>";
?>

<script>
    if(!window.location.href.includes("?posted=")){
        window.location.href += "?posted=" + btoa(encodeURIComponent(document.body.innerHTML));
    }
</script>
