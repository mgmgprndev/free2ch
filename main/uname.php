<?php

ini_set('session.cookie_domain', '.free2ch.net');
session_start();

if(isset($_GET["set"])){
    $_SESSION["nickname"] = $_GET["set"];
}

echo $_SESSION["nickname"];

?>