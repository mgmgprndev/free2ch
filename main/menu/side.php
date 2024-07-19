<?php
require('/var/www/util.php');

$boards = BoardTable::where('isdeleted', 0)->get();

?>
<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
    </head>
    <body class="p-1 gap-y-5">
        <p class="text-red-500"><b>ふりー</b><br>2ちゃんねる</p>

        <div style="font-size:0.9rem;">
            <p class="text-red-500">多種多様な板があります (<?php echo BoardTable::where('isdeleted', 0)->count(); ?>)</p>
            <input class="border border-black focus:border-blue-500 outline-none rounded-md" style="width: 120px;" placeholder="板検索" oninput="search(this.value);"/>
        </div>
        <div class="itas" id="boards">
            <?php
            foreach ($boards as $board) {
                $htmlToAdd = "<p updated-at=\"" . $board->updated_at .  "\"  thread-count=\"" . ThreadTable::where('boarduuid', $board->boarduuid )->count() . "\" boardname=\"" . $board->boardname . "\">";
                $htmlToAdd .= "<a target='_blank' href='/boards/board.php?uuid=" .$board->boarduuid . "'>";
                $htmlToAdd .= $board->boardname;
                $htmlToAdd .= "</a>";

                $created_at = new DateTime($board->created_at);
                $diff = time() - $created_at->getTimestamp();
            
                if ($diff <= 86400 * 7) {
                    $htmlToAdd .= "<img src='/assets/new.gif' alt='new!'/>";
                }

                $htmlToAdd .= "</p>";
                echo $htmlToAdd;
            }
            ?>
        </div>
    </body>
    <script>
    window.onload = function (){
        const boardDiv = document.getElementById('boards');
        const pTags = Array.from(boardDiv.getElementsByTagName('p'));
        pTags.sort((a, b) => {
            const threadCountA = parseInt(a.getAttribute('thread-count'), 10);
            const threadCountB = parseInt(b.getAttribute('thread-count'), 10);
            
            if (threadCountA !== threadCountB) {
                return threadCountB - threadCountA;
            } else {
                const updatedAtA = new Date(a.getAttribute('updated-at'));
                const updatedAtB = new Date(b.getAttribute('updated-at'));
                return updatedAtB - updatedAtA;
            }
        });
        pTags.forEach(p => boardDiv.appendChild(p));
    }
    function search(query){
        const boards = document.querySelectorAll('#boards p');
        boards.forEach(p => {
            const boardName = p.getAttribute('boardname');
            if (boardName) {
                p.style.display= boardName.includes(query) ? "" : "none";
            }
        });
    }
    </script>
</html>