<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
        <meta name="robots" content="index, follow" />
        <meta name="description" content="Free2chへようこそ。『今日の晩御飯』から『量子力学』までをカバーする匿名掲示板群。" />
        <link rel="canonical" href="https://free2ch.net/menu/"/>
        <script>
            if (/Mobi/.test(navigator.userAgent)) {
                window.location.href = '/menu/mobile.php';
            }    
        </script>
    </head>
    <frameset cols="250,*">
        <frame class="fixed" src="/menu/new-side.php"></frame>
        <frame src="/menu/main.php"></frame>
    </frameset>
</html>