<?php
$u = isset($_GET["u"]) ? $_GET["u"] : "";
$c = isset($_GET["c"]) ? $_GET["c"] : "";

if ($u == ""){
    echo "パラメーターが指定されていません!";
    exit;
}

if ($c == ""){
    echo "パラメーターが指定されていません!";
    exit;
}
?>
<h1>リダイレクトしています...</h1>
<script>
setTimeout(function() {
    window.location.href = 'https://shion.free2ch.net?uuid=<?php echo $u ?>&c=<?php echo $c; ?>';
}, 1000);
</script>