<?php
$filename = isset($_GET['id']) ? preg_replace('/[^A-Za-z0-9]/', '', $_GET['id']) : '';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$jsonFile = $_SERVER["DOCUMENT_ROOT"] . "/" . "results/" . $filename . ".json";

if (!file_exists($jsonFile)) {
    http_response_code(404);
    exit("File not found");
}
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);

if ($data === null) {
    http_response_code(500);
    exit("Failed to parse JSON");
}
$perPage = 10;
$startIndex = ($page - 1) * $perPage;
$endIndex = $startIndex + $perPage;
$data["pages"] = ceil(count($data['results']) / $perPage);
$data['results'] = array_slice($data['results'], $startIndex, $perPage);
header('Content-Type: application/json');
echo json_encode($data, JSON_PRETTY_PRINT);
?>