<?php
require('/var/www/util.php');
?>
<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
    </head>
    <body class="p-5 md:p-16 bg-brick">
        <div class="min-h-full w-full bg-green-500">
            <div class="border-double border-4 border-g-1000 min-h-full w-full p-5">
                <h1>ふりー2ちゃんねるへようこそ</h1>
                <p>なんでもありな匿名掲示板群です。</p>
                <p>◾️累計</p>

                <table>
                    <tbody>
                        <tr>
                            <th>板数</th>
                            <th><?php echo BoardTable::where('isdeleted', 0)->count(); ?></th>
                        </tr>
                        <tr>
                            <th>スレッド数</th>
                            <th><?php echo ThreadTable::where('isdeleted', 0)->count(); ?></th>
                        </tr>
                        <tr>
                            <th>書き込み数</th>
                            <th><?php echo CommentTable::where('isdeleted', 0)->count(); ?></th>
                        </tr>
                    </tbody>
                </table>
                <p>◾️ 検索</p>
                <p>検索は <a target="_blank" href="https://search.free2ch.net">こちら</a>から!</p>
                <p>◾️ 板の作成</p>
                <p>ふりー2ちゃんねるでは、誰でも板を作れます。</p>
                <p>作りたい方はこちらからどうぞ: <a target="_blank" href="https://r.free2ch.net">作成</a></p>
                <iframe src="/watch.php" style="width:100%;max-width:500px; height: 500px;background-color:white;padding:0px;"></iframe>
                <p>◾ 削除人&運営&開発者募集中</p>
                <p>運営を手伝ってくれる仲間を探しています！</p>
                <p>手伝ってもいいよ、って方は <a target="_blank" href="mailto:mgmgprndev@gmail.com">mgmgprndev@gmail.com</a> までメールしてください。</p>
                <p>◾️ IP及びユーザーエージェントの収集について</p>
                <p>
                    書き込んだ人のIPとユーザーエージェントをログしています。<br>
                    日本国の法律に反しない限り、法執行機関へ開示は一切しません。<br>
                    また、その他の目的での利用は一切致しません。
                </p>
                <p>◾️ 管理者連絡先</p>
                <p>Email: <a target="_blank" href="mailto:mgmgprndev@gmail.com">mgmgprndev@gmail.com</a></p>
                <p>Discord: mgmgprndev</p>
                <p>◾️ 管理者用: <a target="_top" href="/adminlogin.php">ログイン</a>
                <p>◾️ 照会依頼について(法執行機関用)</p>
                <p>
                    現在特設ページを開発中です。現在はメールにてお願いします。<br>
                    法執行機関であることがわかるドメインを用いたアドレスからの依頼のみ、応じます。(例: go.jp, lg.jp, .gov等)
                </p>
            </div>
        </div>
    </body>
</html>