<?php
require('/var/www/util.php');

if(isset($_GET["getdata"]) && $_GET["getdata"] == "1" ) {
    $boards = BoardTable::where('isdeleted', 0)->orderBy('category')->get();

    $data = [
        "boards" => []
    ];

    foreach ($boards as $board) {
        $created_at = new DateTime($board->created_at);
        $diff = time() - $created_at->getTimestamp();
        $isWeekOld = $diff <= 86400 * 7;

        $data["boards"][] = [
            "updated_at" => $board->updated_at,
            "thread_count" => ThreadTable::where('boarduuid', $board->boarduuid )->where('isdeleted', 0 )->count(),
            "board_name" => $board->boardname,
            "board_uuid" => $board->boarduuid,
            "board_category" => $board->category,
            "created_last_week" => $isWeekOld
        ];
    }

    $jsonOutput = json_encode($data);
    header('Content-Type: application/json');
    echo $jsonOutput;
    exit;
}

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
        </div>
    </body>
    <script>
    window.onload = function (){
        fetch( window.location.href + "?getdata=1").then((response) => response.json()).then((data) => {
            const boards = document.getElementById("boards");
            var lastCategory = "";
            var category = null;
            data.boards.forEach(board => {
                if(board.board_category != lastCategory){
                    var cp = document.createElement("p");
                    cp.style.color = "red";
                    cp.innerHTML = board.board_category;
                    cp.classList.add("board-category")
                    category = document.createElement("div");
                    category.appendChild(cp);
                    category.classList.add("itas");
                    category.classList.add("board_category_div");
                    category.setAttribute("board_category", board.board_category);

                    boards.appendChild(category);
                }
                lastCategory = board.board_category;

                var p = document.createElement("p");
                p.setAttribute("updated_at", board.updated_at);
                p.setAttribute("thread_count", board.thread_count);
                p.setAttribute("board_name", board.board_name);
                p.setAttribute("board_uuid", board.board_uuid);
                p.setAttribute("board_category", board.board_category);
                p.classList.add("board-ptag");
                var a = document.createElement("a");
                a.target = "_blank";
                a.href = "/boards/board.php?uuid=" + board.board_uuid;
                a.innerHTML = board.board_name;
                p.appendChild(a);
                if(board.created_last_week){
                    var img = document.createElement("img");
                    img.src = "/assets/new.gif";
                    img.alt = "new!";
                    p.appendChild(img);
                }
                category.appendChild(p);
            });


            const boardsContainer = document.getElementById('boards');

            boardsContainer.querySelectorAll('.board_category_div').forEach(div => {
                const pTags = Array.from(div.querySelectorAll('p'));
                const boardCategoryTags = pTags.filter(p => p.classList.contains('board-category'));
                const otherPTags = pTags.filter(p => !p.classList.contains('board-category'));
                otherPTags.sort((a, b) => {
                    const threadCountA = parseInt(a.getAttribute('thread_count') || '0', 10);
                    const threadCountB = parseInt(b.getAttribute('thread_count') || '0', 10);
                    const updatedAtA = new Date(a.getAttribute('updated_at') || '1970-01-01');
                    const updatedAtB = new Date(b.getAttribute('updated_at') || '1970-01-01');

                    if (threadCountA !== threadCountB) {
                        return threadCountB - threadCountA;
                    }
                    return updatedAtB - updatedAtA;
                });
                const sortedPTags = [...boardCategoryTags, ...otherPTags];
                sortedPTags.forEach(p => div.appendChild(p));
            }); 

            const sortOrder = ["ラウンジ", "ニュース・実況", "社会・政治", "社会・経済", "法律", "宗教", "理系", "文系", "娯楽", "創作", "技術・開発", "匿名・セキュリティ", "その他", "運営"];
            const divs = Array.from(boards.querySelectorAll('.board_category_div'));

            divs.sort((a, b) => {
                const categoryA = a.getAttribute('board_category') || 'その他';
                const categoryB = b.getAttribute('board_category') || 'その他';
                return sortOrder.indexOf(categoryA) - sortOrder.indexOf(categoryB);
            });
            divs.forEach(div => boards.appendChild(div));
            
        });
    }

    function search(query){
        const boards = document.querySelectorAll('.board-ptag');
        boards.forEach(p => {
            const boardName = p.getAttribute('board_name');
            if (boardName) {
                p.style.display= boardName.includes(query) ? "" : "none";
            }
        });

        const categories = document.querySelectorAll('.board-category');
        categories.forEach(cp => {
            i = 0;
            document.querySelectorAll('.board-ptag').forEach(p => {
                if(cp.innerHTML == p.getAttribute("board_category") && p.style.display != "none" ){
                    i++;
                }
            });
            cp.style.display = i == 0 ? "none" : "";
        });
    }
    </script>
</html>