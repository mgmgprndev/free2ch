<?php

if(isset($_GET["live"])){

    $amountRequested = intval( $_GET["live"] == "" ? 1 : $_GET["live"] );

    function cutText($text) {
        if (mb_strlen($text) > 25) {
            return mb_substr($text, 0, 25) . '...';
        }
        return $text;
    }

    require('/var/www/util.php');

    $comments = CommentTable::orderBy('created_at', 'desc')
        ->where('isdeleted', 0)
        ->take(100)
        ->get();

    header('Content-Type: application/json');
    $response = array(
        'comments' => array()
    );

    $amountResponded = 0;
    foreach($comments as $comment){
        if($amountResponded >= $amountRequested){
            continue;
        }

        $thread = ThreadTable::where('threaduuid',$comment->threaduuid)->first();
        $board = BoardTable::where('boarduuid',$thread->boarduuid)->first();


        if( $board->category == "運営" ){
            continue;
        }

        $bname = $board->boardname;
        $tname = $thread->threadname;
        $comment_at = cutText($tname) . "<span class='sm'>@" . cutText($bname) . "</span>";
        $comment_text = cutText($comment->context);
        $comment_by = cutText($comment->nickname) . "(" . $comment->useruuid . ")";
        $href = "https://shion.free2ch.net?uuid=" . $thread->threaduuid . "&c=" . $comment->commentuuid;


        $commentToAdd = array(
            'comment_at' => $comment_at,
            'comment_text' => $comment_text,
            'comment_by' => $comment_by,
            'is_admin' => $comment->isadmin == 1,
            'href' => $href,
            'uuid' => $comment->commentuuid
        );

        $response["comments"][] = $commentToAdd;

        $amountResponded++;
    }
    
    $response["comments"] = array_reverse($response["comments"]);
        
    echo json_encode($response);

    exit;
}
?>

<style>
* { 
    margin: 0; 
}

html, body {
    min-height: 100%;
    width: 100%;
    padding: 5px;
    display: flex;
    flex-direction: column;
    gap: 5px;
    box-sizing: border-box;
}

body > a {
    text-decoration: none;
    color: white;
    background-color: gray;
    border-radius: 15px;
    height: auto;
    max-height: 5.5rem;
    padding: 5px;
    overflow: hidden;
    box-sizing: border-box;
}

body > a > .context {
    font-size: 15px;
}

body > a > .s {
    font-size: 12px;
}

body > a > .sm {
    font-size: 10px;
}

body > a:hover {
    background-color: darkgray;
}

</style>

<body>
<a id="fetching">取得しています...</a>
</body>

<script>
const comments = document.body;

function addComment(href, comment_at, comment_text, isAdmin, comment_by, uuid){
    if( document.getElementById(uuid) != null ) {
        return null;
    }
    var a = document.createElement("a");
    a.target = "_blank";
    a.href = href;
    var p1 = document.createElement("p");
    p1.classList.add("s");
    p1.innerHTML = comment_at;
    var p2 = document.createElement("p");
    p2.classList.add("context");
    p2.innerHTML = "「" + comment_text + "」";
    var p3 = document.createElement("p");
    p3.classList.add("s");
    p3.innerHTML = comment_by;
    if(isAdmin){
        p3.style.color = "red";
    }

    a.appendChild(p1);
    a.appendChild(p2);
    a.appendChild(p3);
    a.id = uuid;
    return a;
}

window.onload = function() {
    setInterval(() => {
        fetchIt();

        let elements = document.querySelectorAll("a");
        while (elements.length > 10) {
            elements[elements.length - 1].parentNode.removeChild(elements[elements.length - 1]);
            elements = document.querySelectorAll("a");
        }
    }, 1000);


    setInterval(() => {
        window.location.reload();
    }, 1000 * 60 );
}


function fetchIt(){
    fetch("/watch.php?live=10")
    .then((response) => response.json())
    .then((data) => {
        data.comments.forEach((comment) => {
            var r = addComment(comment.href, comment.comment_at, comment.comment_text, comment.is_admin, comment.comment_by, comment.uuid);
            if(r != null){
                comments.insertBefore(r, comments.firstChild);
            }
        });
    });
}

</script>