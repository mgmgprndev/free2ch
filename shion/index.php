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
                    <a target="_blank" style="width: fit-content;" href="https://admin.free2ch.net/confirm.php?target=thread&uuid=<?php echo $_GET["uuid"]; ?>">このスレッドを削除</a>
                    <a target="_blank" style="width: fit-content;" href="https://admin.free2ch.net/checkip.php?target=thread&uuid=<?php echo $_GET["uuid"]; ?>">スレッドを作った人の情報を確認</a>
                <?php } ?>
                
                <p style="cursor:pointer;" onclick="window.open('https://free2ch.net/boards/board.php?uuid=<?php echo $thread->boarduuid;  ?>','_blank')"><?php echo BoardTable::where('boarduuid', $thread->boarduuid)->first()->boardname; ?></p>
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
                        if($comment->isdeleted == 1){
                            ?>
                            <comment>
                                <nushi>
                                    <p><?php echo $i; ?></p>
                                    <p>：</p>
                                    <name><span style="color:red; font-weight: bold;">[DELETED]</span></name>
                                    <p>：</p>
                                    <p><?php echo $comment->created_at; ?></p>
                                    <p>ID:<span style="color:red; font-weight: bold;">[DELETED]</span></p>
                                    <!-- CommentUUID:<?php echo $comment->commentuuid; ?>  -->
                                </nushi>
                                <context>
                                    <p><span style="color:red; font-weight: bold;">[DELETED]</span></p>
                                </context>
                            </comment>
                            <?php 
                            continue;
                        }
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

                <comment id='<?php echo $comment->commentuuid; ?>' data-id='<?php echo $i; ?>'>
                    <nushi>
                        <p><?php echo $i; ?></p>
                        <p>：</p>
                        <name class="<?php echo $comment->isadmin == 1 ? "text-red-500" : ""; ?>"><?php echo $comment->nickname; ?></name>
                        <p>：</p>
                        <p><?php echo $comment->created_at; ?></p>
                        <p>ID:<span style='font-size:13px; <?php echo $comment->isadmin == 1 ? "color:red;" : ""; ?>'><?php echo $comment->useruuid; ?><?php echo $comment->isadmin == 1 ? "<span style='font-size:9px;'>?</span>" : ""; ?><?php echo $comment->useruuid == $comments[0]->useruuid ? "<span style='text-decoration:underline;'>主</span>" : ""; ?></span></p>
                        <?php if ($isAdmin) { ?>
                            <p class="text-xs"><a target="_blank" href="https://admin.free2ch.net/confirm.php?target=comment&uuid=<?php echo $comment->commentuuid; ?>">このコメントを削除</a></p>
                            <p class="text-xs"><a target="_blank" href="https://admin.free2ch.net/checkip.php?target=comment&uuid=<?php echo $comment->commentuuid; ?>">書き込んだ人の情報を確認</a></p>
                        <?php } ?>
                    </nushi>
                    <context style="word-break: break-all;">
                        <?php
                            $context = $comment->context;
                            $contextlines = explode("\n",$context);
                            foreach($contextlines as $line){
                                echo "<p>" . $line . "</p>";
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
                        <input name="nickname" type="text" placeholder="お名前 (最大 35文字)" minlength="1" maxlength="35" required/>
                        <textarea name="comment" placeholder="コメント (最大 2048文字)" minlength="1" maxlength="2048" required></textarea>
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
        <script src="https://shion.free2ch.net/chomusuke.js"></script>
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