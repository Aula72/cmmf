<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = '';
$data = json_decode(file_get_contents("php://input"), true);
echo json_encode($helper->get_token());