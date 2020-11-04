<?php
session_start();
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$dataEndLen = strlen($dados['end']);
$dataStrLen = strlen($dados['start']);

$data_start = str_replace('/', '-', $dados['start']);
$data_start_conv = date("Y-m-d H:i:s", strtotime($data_start));

$data_end = str_replace('/', '-', $dados['end']);
$data_end_conv = date("Y-m-d H:i:s", strtotime($data_end));
$dataStrLen = strlen($data_start_conv);


include "./conexao.php";

$sql = "INSERT INTO tb_eventos (`usuario`,`title`, `color`, `start`, `end`) VALUES (:usuario, :title, :color, :start, :end)";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':usuario',$dados['usuario']);
$stmt->bindParam(':title', $dados['title']);
$stmt->bindParam(':color',$dados['color']);
$stmt->bindParam(':start',$data_start_conv);
$stmt->bindParam(':end',$data_end_conv);

if (!($dataEndLen < 19 || $dataStrLen < 19 || $dados['end'] == "" || $dados['usuario'] == ""|| $dados['start'] === $dados['end'])){
    $stmt->execute();
    $ret = ['sit' => true, 'msg' => "<div class='alert alert-success' role='alert'>Evento cadastrado com sucesso!</div>"];
    $_SESSION['msg'] = $ret['msg'];
}else {
    $ret = ['sit' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Não foi possivel cadastrar, verifique os campos!</div>"];
}

header('Content-Type: application/json'); //utilizado para indicar o tipo de arquivo
echo json_encode($ret);
