<?php
include 'conexao.php';

//$conn = new PDO("mysql:host=localhost;dbname=db_agenda", "root", "");
$sql = "SELECT id, title, color, start, end FROM tb_eventos";
$stmt = $conn->prepare($sql);
$stmt->execute();

$eventos = [];

while ($row_events = $stmt->fetch(PDO::FETCH_ASSOC)){
    $id = $row_events['id'];
    $title = $row_events['title'];
    $color = $row_events['color'];
    $start = $row_events['start'];
    $end = $row_events['end'];

    $eventos[] = [
        'id' => $id,
        'title' => $title,
        'color' => $color,
        'start' => $start,
        'end' => $end
    ];
}

echo json_encode($eventos);