<?php
session_start();



if(isset($_SESSION["credit"])){
    if($_SESSION["credit"] <= 0){
        echo "クレジットが足りません。";
        exit;
    }else {
        $_SESSION["credit"] = $_SESSION["credit"] - 1;
    }
}else {
    echo "クレジットが足りません。";
    exit;
}

function searchMatch($i1,$i2){
    if($i2 == ""){
        return true;
    }
    $s1 = explode(" ",$i1);
    $s2 = explode(" ",$i2);
    foreach($s1 as $d1){
        foreach($s2 as $d2){
            if(str_contains($d1,$d2)){
                return true;
            }
        }
    }
    return false;
}

function fun_text($i){
    return isset($i) ? $i : "";
}

$all   = fun_text($_GET["all"]);
$b_name   = fun_text($_GET["b_name"]);
$t_name   = fun_text($_GET["t_name"]);
$c_userid = fun_text($_GET["c_userid"]);
$c_name   = fun_text($_GET["c_name"]);
$c_text   = fun_text($_GET["c_text"]);

$is   = fun_text($_GET["is"]);
$isAdvanced = $is == "2";

$data = [
    'status' => '',
    'query' => [
        'all' => $all,
        'b_name' => $b_name,
        't_name' => $t_name,
        'c_userid' => $c_userid,
        'c_name' => $c_name,
        'c_text' => $c_text,
        'limit_admin' => ''
    ],
    'results' => [],
    'count' => 0
];


$search_id =  substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 25);
require_once('/var/www/util.php');

$comments = CommentTable::orderBy('created_at', 'desc')->get();

$count = 0;

foreach ($comments as $comment){
    $thread = ThreadTable::where('threaduuid', $comment->threaduuid)->first();
    $board = BoardTable::where('boarduuid', $thread->boarduuid)->first();
    if(
        ( !$isAdvanced && (searchMatch($board->boardname, $all ) ||
        searchMatch($thread->threadname, $all ) ||
        ( $comment->useruuid == $all ) ||
        searchMatch($comment->nickname, $all ) ||
        searchMatch($comment->context, $all))) ||

        ( $isAdvanced && (searchMatch($board->boardname, $b_name ) &&
        searchMatch($thread->threadname, $t_name ) &&
        ( $c_userid == "" || $comment->useruuid == $c_userid ) &&
        searchMatch($comment->nickname, $c_name ) &&
        searchMatch($comment->context, $c_text )))
    ){
        $data["results"][] = [
            'id' => $comment->id,
            'userid' => $comment->useruuid,
            'nickname' => $comment->nickname,
            'context' => $comment->context,
            'isadmin' => $comment->isadmin,
            'boardname' => $board->boardname,
            'threadname' => $thread->threadname,
            'boarduuid' => $thread->boarduuid,
            'threaduuid' => $thread->threaduuid,
            'commentuuid' => $comment->commentuuid
        ];
        $count++;
    }
}

$data["count"] = $count;

$json = json_encode($data, JSON_PRETTY_PRINT);
$file = "results/" . $search_id . '.json';
file_put_contents($file, $json);

?>

<script>
window.location.href = "/search.php?id=<?php echo $search_id ?>&p=1";
</script>