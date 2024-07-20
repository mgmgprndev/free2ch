<?php
if(!isset($_GET["to"]) || $_GET["to"] == ""){
    echo "<script>window.close();</script>";
    exit;
}

$url = urldecode(base64_decode($_GET["to"]));

?>
<style>
* { 
    margin:0; 
}

body { 
    padding: 1.25rem;
    display:flex;
    flex-direction:column;
    gap:1.25rem; 
} 

button {
    padding: 1.25rem;
    width:500px;
    font-size:1.5rem;
}
</style>
<h1>このURLへとジャンプしますか?</h1>
<input id="url" value="<?php echo $url; ?>" style="padding: 1.25rem; width:500px;font-size:1.5rem;" readonly>

<button onclick='window.open(document.getElementById("url").value,"_self");'>はい</button> <button onclick='window.close();'>いいえ</button>