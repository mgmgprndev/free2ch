<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
        <script>
            if (!(/Mobi/.test(navigator.userAgent))) {
                window.location.href = '/menu/';
            }    
        </script>
    </head>
    <body class="flex flex-col gap-x-5 p-5 overflow-y-hidden max-h-screen">
        <button class="py-2 px-5 w-fit border border-black rounded-md hover:bg-black hover:text-white" onclick="document.getElementById('menu').classList.toggle('hidden');">メニューを開く</button>
        <div class="h-full w-full fixed left-0 top-0 flex flex-col gap-5 bg-white p-5 hidden" id="menu">
            <button class="py-2 px-5 w-fit border border-black rounded-md hover:bg-black hover:text-white" onclick="document.getElementById('menu').classList.toggle('hidden');">メニューを閉じる</button>
            <iframe src="/menu/side.php" class="h-full w-full p-0"></iframe>
        </div>
        <iframe src="/menu/main.php" class="h-full w-full p-0"></iframe>
        <p class="text-sm">ふりー２ちゃんねるはPCでの利用を強く推奨します</p>
    </body>
</html>