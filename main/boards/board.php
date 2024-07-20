<?php
require('/var/www/util.php');

$board = BoardTable::where('boarduuid', $_GET["uuid"])->where('isdeleted', 0)->first();

if(!$board){
    echo "<script>window.location.href='/menu';</script>";
    exit;
}

$threads = ThreadTable::where('boarduuid', $_GET['uuid'])
    ->orderBy('last_comment', 'desc')
    ->where('isdeleted', 0)
    ->take(100)
    ->get();


require_once('/var/www/id.php');
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ふりー２ちゃんねる (板:<?php echo $board->boardname; ?>)</title>
        <link rel="stylesheet" href="/assets/style.css"/>
        <meta name="robots" content="index, follow" />
        <meta name="description" content="スレッド数が(<?php echo ThreadTable::where('boarduuid', $_GET["uuid"])->count(); ?>)個の「<?php echo $board->boardname; ?>」へようこそ!" />
    </head>
    <body class="p-5 md:p-16 bg-brick">
        <div class="card bg-green-500">
            <div>
                <?php 
                $user = VerifyTable::where('userkey', $_SESSION["userkey"])->first();
                if($user && $user->isadmin == 1){?>
                    <a target="_blank" href="https://admin.free2ch.net/confirm.php?target=board&uuid=<?php echo $_GET["uuid"]; ?>">この板を削除</a>
                    <a target="_blank" href="https://admin.free2ch.net/checkip.php?target=board&uuid=<?php echo $_GET["uuid"]; ?>">板を作った人の情報を確認</a>
                <?php } ?>

                <h1>「<?php echo $board->boardname; ?>」へようこそ!</h1>
                <?php
                    $desc = $board->boarddescription;
                    $descLines = explode("\n",$desc);
                    foreach($descLines as $line){
                        echo "<p>" . $line . "</p>";
                    }
                ?>

                <br>

                <div class="formdiv">
                    <form action="/boards/thread.php" method="post" onsubmit="return handleSubmit(event, this);">
                        <input name="thread-name" type="text" placeholder="スレッド名" required/>
                        <input name="nickname" type="text" placeholder="お名前" required/>
                        <textarea name="comment" placeholder="コメント" required></textarea>
                        <input type="hidden" name="board-uuid" value="<?php echo $_GET["uuid"]; ?>">
                        <button>スレッドを作成</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="card bg-stone-200">
            <div>
                <p>◾️ 直近ではこのようなスレッドが作成されました</p>
                <div class="flex flex-row flex-wrap gap-x-5">
                    <?php
                    foreach ($threads as $thread) {
                        $htmlToAdd = "<p>";
                        $htmlToAdd .= "<a target='_blank' href='https://shion.free2ch.net?uuid=" . $thread->threaduuid . "'>";
                        $htmlToAdd .= $thread->threadname . "(" . CommentTable::where('threaduuid', $thread->threaduuid)->count() .  ")";
                        $htmlToAdd .= "</a>";
                        $htmlToAdd .= "</p>";
                        echo $htmlToAdd;
                    }
                    ?>
                </div>
            </div>
        </div>
        <script src="https://free2ch.net/nickname.js"></script>
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
    </body>
</html>