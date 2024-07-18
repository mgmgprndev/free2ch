<?php
if(!isset($_GET["id"])){
    exit;
}

if(!isset($_GET["p"])){
    exit;
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ふりー２ちゃんねる</title>
        <link rel="stylesheet" href="/assets/style.css"/>
    </head>
    <body class="bg-stone-200 p-5">
        <p><a href="/">検索に戻る</a></p>
        <script src="/search.js"></script>
        <div class="result_search" id="results">

        </div>
        <script>
            getResults("<?php echo $_GET["id"]; ?>", <?php echo $_GET["p"]; ?>);
        </script>
        <?php
        $param = "?id=" . $_GET["id"] . "&p=";
        ?>
        <div class="fixed left-0 right-0 bottom-0 h-auto w-full flex flex-row gap-x-5 justify-center bg-stone-200 py-2 border-t border-black items-center">
            <a href="/search.php<?php echo $param; ?>" id="first">FIRST</a>
            <a href="/search.php<?php echo $param; ?>" id="prev">PREV</a>

            <form action="/search.php" method="get" class="m-0 p-0">
                <input type="hidden" name="id" value="<?php echo $_GET["id"]; ?>"/>
                <input type="number" name="p" class="bg-transparent border border-black p-1 w-24 rounded-md text-center" min="1" value="" id="custom-pg">
            </form>
            
            <a href="/search.php<?php echo $param; ?>" id="next">NEXT</a>
            <a href="/search.php<?php echo $param; ?>" id="last">LAST</a>
        </div>
    </body>
</html>
