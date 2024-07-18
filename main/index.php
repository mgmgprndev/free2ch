<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
    </head>
    <body>
        <h1>このサイトはJavaScriptを利用します!</h1>
        <p>ページロード後も、この画面が表示されたまま変わらない場合、JavaScriptが無効化されている可能性がございます。</p>
        <p><a href="/">再読み込み</a></p>
    </body>

    <script>
        document.body.innerHTML = `<h1>ふりー２ちゃんねるへようこそ</h1>
        <div class="mx-auto text-center flex flex-col gap-5">
            <p>『今日の晩御飯』から『量子力学』までをカバーする匿名掲示板群へようこそ</p>
            <h2>[ <a id="join" href="/menu">入る</a> ]</h2>
            <p>しばらくすると自動的に移動します。(<span id="time">10.00</span>)</p>
            <p>著作権フリー、転載自由、スレッドへのリンクを貼ってね。</p>
        </div>`;

        var time = 1000;
        setInterval(function (){
            time--;
            timestr = time > 0 ? time : 0;
            timestr = (timestr / 100).toString();
            timestr += '0'.repeat(3 - timestr.replace(".","").length);
            if(timestr=="000") {
                timestr = "0.00";
            }
            document.getElementById("time").innerHTML = timestr;
            if(time==0){
                document.getElementById('join').click();
            }
        },1);
    </script>
</html>