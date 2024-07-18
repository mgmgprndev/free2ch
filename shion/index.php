<?php
require('/var/www/util.php');
require('/var/www/id.php');

if(!isset($_GET["uuid"])){
    echo "<script>window.location.href='https://free2ch.net/menu';</script>";
    exit; 
}

$thread = ThreadTable::where('threaduuid', $_GET["uuid"])->where('isdeleted', 0)->first();

if(!$thread){
    echo "<script>window.location.href='https://free2ch.net/menu';</script>";
    exit;
}


?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ふりー２ちゃんねる</title>
        <link rel="stylesheet" href="/assets/style.css"/>
    </head>
    <body class="p-5 md:p-16 bg-brick">
    <div class="card bg-stone-200">
            <div class="flex flex-col gap-5">
                <?php 
                $user = VerifyTable::where('userkey', $_SESSION["userkey"])->first();
                $isAdmin = $user && $user->isadmin == 1;
                if($isAdmin) { ?>
                    <a target="_blank" href="https://admin.free2ch.net/confirm.php?target=thread&uuid=<?php echo $_GET["uuid"]; ?>">このスレッドを削除</a>
                <?php } ?>
                
                <h1 class="text-red-500"><?php echo $thread->threadname; ?></h1>
                
                <div class="w-full border-t border-black"></div>

                <?php 
                    $comments = CommentTable::where('threaduuid', $_GET["uuid"])->orderBy('created_at', 'asc')->get();
                    $i = 0;
                    $c = isset($_GET["c"]) ? $_GET["c"] : "";

                    if($c != ""){
                        echo "<p>【コメント指定パラメーターが追加されています】</p>";
                        echo "<p>パラメーター: " . str_replace(",", ", ", $c) . "</p>";
                        echo "<a href='/?uuid=" . $_GET["uuid"] . "'>全て読み込む</a>";
                        echo '<div class="w-full border-t border-black"></div>';
                    }

                    foreach($comments as $comment){
                        $i += 1; 
                        if($c != ""){
                            $cl = explode(",", $c);
                            $isMatch = false;
                            foreach($cl as $ll){
                                if($ll == strval($i) || $ll == $comment->commentuuid ){
                                    $isMatch = true;
                                }
                            }
                            if(!$isMatch){
                                continue;
                            }
                        }
                ?>

                <comment>
                    <nushi>
                        <p><?php echo $i; ?></p>
                        <p>：</p>
                        <name class="<?php echo $comment->isadmin == 1 ? "text-red-500" : ""; ?>"><?php echo $comment->nickname; ?></name>
                        <p>：</p>
                        <p><?php echo $comment->created_at; ?></p>
                        <p>ID:<?php echo $comment->useruuid; ?><?php echo $comment->isadmin == 1 ? "<span class='text-xs text-red-500'>?</span>" : ""; ?></p>
                        <!-- CommentUUID:<?php echo $comment->commentuuid; ?>  -->
                        <?php if ($isAdmin) { ?>
                            <p class="text-xs"><a target="_blank" href="https://admin.free2ch.net/confirm.php?target=comment&uuid=<?php echo $comment->commentuuid; ?>">このコメントを削除</a></p>
                        <?php } ?>
                    </nushi>
                    <context>
                        <?php
                            $context = $comment->context;
                            $contextlines = explode("\n",$context);
                            foreach($contextlines as $line){
                                echo $line . "<br>";
                            }
                        ?>
                    </context>
                </comment>

                <?php
                    }
                ?>

                <div class="w-full border-t border-black"></div>

                <?php if($thread->readonly ==0){ ?>
                <div class="formdiv">
                    <form action="/comment.php" method="post" onsubmit="return handleSubmit(event, this);">
                        <input name="nickname" type="text" placeholder="お名前" required/>
                        <textarea name="comment" placeholder="コメント" required></textarea>
                        <input name="thread-uuid" type="hidden" value="<?php echo $_GET["uuid"]; ?>"/>
                        <button>書き込み</button>
                    </form>
                </div>
                <?php } else { ?>
                    <p>このスレッドは書き込みできません。</p>
                <?php } ?>
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