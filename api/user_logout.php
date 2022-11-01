<?php 
header("Content-Type: application/json");
require_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$id = $_GET['token'];
$helper->query("delete from tokens where token=:token",[':token'=>$id]);

echo json_encode(["message"=>"You are logged out..."]);