<?php
require '../../conexion.php';
$id = intval($_POST['id']);
$res = $conn->query("SELECT estatus FROM cotizaciones WHERE id=$id");
$dat = $res->fetch_assoc();
if($dat['estatus']!='Cotización'){
    echo json_encode(["success"=>false,"message"=>"No se puede cancelar este estado."]);
    exit();
}

if($conn->query("UPDATE cotizaciones SET estatus='Cancelado' WHERE id=$id")){
    echo json_encode(["success"=>true]);
}else{
    echo json_encode(["success"=>false,"message"=>$conn->error]);
}
