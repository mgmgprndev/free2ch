<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
        <script>
            if (/Mobi/.test(navigator.userAgent)) {
                window.location.href = '/menu/mobile.php';
            }    
        </script>
    </head>
    <frameset cols="250,*">
        <frame class="fixed" src="/menu/side.php"></frame>
        <frame src="/menu/main.php"></frame>
    </frameset>
</html>