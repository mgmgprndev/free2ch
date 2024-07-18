<?php
require('/var/www/util.php');

$boards = BoardTable::orderBy('created_at', 'desc')->take(100)->get();

?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ふりー２ちゃんねる</title>
        <link rel="stylesheet" href="/assets/style.css"/>
        <script src="/hash.js"></script>
        <script>
            function toggle_form(s){
                var create = document.getElementById('create');
                var edit = document.getElementById('edit');
                if(s==0){
                    create.style.display = "";
                    edit.style.display = "none";
                }else if(s==1){
                    create.style.display = "none";
                    edit.style.display = "";
                }
            }

            function getDetails(){
                uuid = document.getElementById('board-uuid').value;
                fetch("/get.php?uuid=" + uuid).then((response) => response.json()).then((data) => {
                    if(data.status){
                        document.getElementById('board-name').value = data.data.name;
                        document.getElementById('board-description').value = data.data.description;
                    }
                });
            }
        </script>
    </head>
    <body class="p-5 md:p-16 bg-brick">
        <div class="card bg-green-500">
            <div>
                <h1>ふりー2ちゃんねる、板作成場</h1>

                <p>ふりー2ちゃんねるでは、誰でも板を作れます</p>
                <p>◾️ 以下に該当する板は運営の裁量で削除される可能性があります</p>
                <p>暴力または詐欺、危害を目的とした板及びカテゴリー</p>
                <p>日本国の法律に抵触する行為を目的とした板及びカテゴリー</p>
                <p>公序良俗を過度に反する行為を目的とした板及びカテゴリー</p>
                <p>現実の過度にグロまたはセンシティブな話題を取り扱うことを目的とした板、またはカテゴリー</p>
                <p>重複する板、スパム目的の板、不当な目的の板</p>
                <p class="text-red-500">上記に該当する板を繰り返し作成した場合、IPブラックリストに登録する可能性があります。</p>

                <br>


                <div class="formdiv">
                    <div class="w-full flex flex-row gap-x-2 py-2">
                        <button onclick="toggle_form(0);" class="w-1/2 py-2 border border-black rounded hover:bg-black hover:text-white">新規に作成</button>
                        <button onclick="toggle_form(1);" class="w-1/2 py-2 border border-black rounded hover:bg-black hover:text-white">既存のを編集</button>
                    </div>
                    <form id="create" action="/create.php" method="post" onsubmit="return handleSubmit(event, this);">
                        <input name="board-name" type="text" placeholder="板名(e.g. スペイン法学板)" required/>
                        <textarea name="board-description" placeholder="板の説明 (e.g. スペインの法学を学ぶ板)" required></textarea>
                        <input oninput="document.getElementById('password').value=sha256(this.value);" type="password" placeholder="板管理用パスワード" required/>
                        <input id="password" name="password" type="hidden"/>
                        <button>板を作成</button>
                    </form>

                    <form id="edit" action="/edit.php" method="post" style="display: none;" onsubmit="return handleSubmit(event, this);">
                        <input id="board-uuid" name="board-uuid" type="text" placeholder="スレッドUUID" required/>
                        <input onclick='getDetails();' type="button" value="板の情報を取得">
                        <br>
                        <input id="board-name" name="board-name" type="text" placeholder="板名(e.g. スペイン法学板)" required/>
                        <textarea id="board-description" name="board-description" placeholder="板の説明 (e.g. スペインの法学を学ぶ板)" required></textarea>
                        <br>

                        <input oninput="document.getElementById('edit-password').value=sha256(this.value);" type="password" placeholder="パスワード" required/>
                        <input id="edit-password" name="password" type="hidden"/>

                        <input oninput="document.getElementById('edit-new-password').value=sha256(this.value);" type="password" placeholder="新規パスワード (変更する場合)"/>
                        <input id="edit-new-password" name="new-password" type="hidden"/>

                        <input type="submit" value="板を編集">
                    </form>
                </div>
            </div>
        </div>

        <div class="card bg-stone-200">
            <div>
                <p>◾️ 直近ではこのような板が作成されました</p>
                <div class="flex flex-row flex-wrap gap-x-5">
                    <?php
                    foreach ($boards as $board) {
                        $htmlToAdd = "<p>";
                        $htmlToAdd .= "<a target='_blank' href='https://free2ch.net/boards/board.php?uuid=" . $board->boarduuid . "'>";
                        $htmlToAdd .= $board->boardname;
                        $htmlToAdd .= "</a>";
                        $htmlToAdd .= "</p>";
                        echo $htmlToAdd;
                    }
                    ?>
                </div>
            </div>
        </div>
    </body>
    <script>
        let isSubmitting = false;
        function handleSubmit(event,i) {
            if (isSubmitting) {
                return false; 
            }
            isSubmitting = true;
            i.submit();
        }
    </script>
</html>