<?php
require('/var/www/util.php');

$board = BoardTable::where('boarduuid', $_GET["uuid"])->first();

header('Content-Type: application/json');

$response = array(
    'status' => false,
    'data' => array()
);

if($board){
    $response = array(
        'status' => true,
        'data' => array(
            'name' => $board->boardname,
            'description' => $board->boarddescription
        )
    );
}

echo json_encode($response);
?>
