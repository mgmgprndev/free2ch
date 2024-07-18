<?php
session_start();


if (isset($_SESSION["lastcredit"]) && isset($_SESSION["credit"])) {
    $diff = strtotime('now') - $_SESSION["lastcredit"];
    if ($diff >= 86400) {
        if ($_SESSION["credit"] <= 0) {
            $_SESSION["credit"] = 100;
        }
    }
}

if(!isset($_SESSION["credit"]) || !isset($_SESSION["lastcredit"])){
    $_SESSION["credit"] = 100;
    $_SESSION["lastcredit"] = strtotime('now');
}

?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ふりー２ちゃんねる</title>
        <link rel="stylesheet" href="/assets/style.css"/>
    </head>
    <body class="bg-stone-200 p-5">
        <div class="h-auto w-full flex flex-col">
            <div class="formdiv mx-auto">
                <p class="text-center">あなたは<?php echo $_SESSION["credit"]; ?>クレジット保有しています。</p><br>

                <form action="/search-rq.php" method="get" onsubmit="return handleSubmit(event,this)">
                    <select onchange="toggle(this)" name="is">
                        <option value='1' selected>一致検索</option>
                        <option value='2'>高度検索</option>
                    </select>

                    <input class="na" style="display: block;" type="text" name="all" placeholder="一致検索(対象:全て)">

                    <input class="a" style="display:none;" type="text" name="b_name" placeholder="板名で絞り込み">
                    <input class="a" style="display:none;" type="text" name="t_name" placeholder="スレッドタイトルで絞り込み">
                    <input class="a" style="display:none;" type="text" name="c_userid" placeholder="書き込みした人のユーザIDで絞り込み">
                    <input class="a" style="display:none;" type="text" name="c_name" placeholder="書き込みした人の名前で絞り込み">
                    <input class="a" style="display:none;" type="text" name="c_text" placeholder="書き込みの内容で絞り込み">

                    <!--<div class="flex flex-row gap-x-2 text-xs">
                        <input type="checkbox">
                        <p>検索対象を運営の書き込みに限定する</p>
                    </div>-->
                    <input onclick="" type="submit" class="disabled:cursor-not-allowed" <?php echo $_SESSION["credit"] <= 0 ? "disabled" : ""; ?> value="検索する (1クレジット消費)" />
                </form>
                <p class="text-center text-xs">(。≧ω≦。)ノ《《検索機能が》》☆･ﾟ:*(´ω｀*人)<br>ｷﾀ━━━━━━━━m9( ﾟ∀ﾟ)━━━━━━━━!!</p><br>

                <div class="text-center w-full border-2 border-green-500">
                    <div class="bg-green-500 text-white py-1">クレジットって?</div> 
                    <p>
                        1クレジットで1回検索できます。<br>クレジットが0になった場合は最終付与から1日経過している場合のみ100クレジット付与されます。<br>
                        この仕様は検索連投でサーバーに負荷がかかるかも、、、って考えたからです。
                    </p>
                    <div class="bg-green-500 text-white py-1">一致検索と高度検索の違いは?</div> 
                    <p>
                        一致検索は一部でも一致すれば、結果に出ます。高度検索は指定された場所が一致している必要があります。<br>
                        例えば、一致検索で「2ch」と調べると、名前に2chが入っていようが、スレッドタイトルに2chが入っていようが、全てが表示されます。<br>
                        高度検索で「2ch」をスレッドタイトルに指定して検索すると、スレッドタイトルに2chが入っている、全ての投稿が表示されます。
                    </p>
                    <div class="bg-green-500 text-white py-1">ちなみに...</div> 
                    <p>検索結果のURL<br><span class="text-xs">(例: https://search...../search.php?id=kd.......8z&p=1)</span><br>は共有することができますが、<br>検索時点の情報から更新されません。<br>また、通常は1日で削除されます。</p>
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


    function toggle(t){
        if(t.value=="1"){
            document.getElementsByClassName('na')[0].style.display="block";
            document.querySelectorAll('.a').forEach((e) => {
                e.style.display = "none";
            });
        }else if(t.value=="2"){
            document.getElementsByClassName('na')[0].style.display = "none";
            document.querySelectorAll('.a').forEach((e) => {
                e.style.display = "block";
            });
        }
    }
    </script>
</html>