<?php
require('/var/www/util.php');

$boards = BoardTable::all();

?>
<html>
    <head>
        <?php require($_SERVER['DOCUMENT_ROOT'] . '/shared/head.php'); ?>
    </head>
    <body class="p-1 gap-y-5">
        <p class="text-red-500"><b>ふりー</b><br>2ちゃんねる</p>
        <div class="itas" id="boards">
            <p class="text-red-500">多種多様な板があります (<?php echo BoardTable::count(); ?>)</p>
            <input class="border border-black focus:border-blue-500 outline-none rounded-md" style="width: 120px;" placeholder="板検索" oninput="search(this.value);"/>

            <?php
            foreach ($boards as $board) {
                $htmlToAdd = "<p boardname=\"" . $board->boardname . "\">";
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
    function search(query){
        const boards = document.querySelectorAll('#boards p');
        boards.forEach(p => {
            const boardName = p.getAttribute('boardname');
            if (boardName) {
                p.style.display= boardName.startsWith(query) ? "" : "none";
            }
        });
    }
    </script>
</html>