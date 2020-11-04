<?php
session_start();
include 'conexao.php';

//$conn = new PDO("mysql:host=localhost;dbname=db_agenda", "root", "");
$sql = "SELECT usuario, id, title, color, start, end FROM tb_eventos";
$stmt = $conn->prepare($sql);
$stmt->execute();

$eventos = [];

while ($row_events = $stmt->fetch(PDO::FETCH_ASSOC)){
    $usuario = $row_events['usuario'];
    $id = $row_events['id'];
    $title = $row_events['title'];
    $color = $row_events['color'];
    $start = $row_events['start'];
    $end = $row_events['end'];

    $eventos[] = [
        'usuario' => $usuario,
        'id' => $id,
        'title' => $title,
        'color' => $color,
        'start' => $start,
        'end' => $end
    ];
}
echo json_encode($eventos);