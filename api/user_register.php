<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";

$help = new \Cmmf\Helper;

$data = json_decode(file_get_contents("php://input"), true);

$uname = $data["uname"];
$mail = $data["mail"];
$status = 2;

$help->req_method('post');

$check = $help->query("select * from user where uname=:uname or mail=:mail",[":uname"=>$uname, ":mail"=>$mail]);

if($check->rowCount()>0){
	$msg["status"] = 0;
	$msg["message"] = "Account already exists";
}else{
	$msg["status"] = 1;
	$help->query("insert into  user set uname=:uname, mail=:mail, status=:status",[":uname"=>$uname, ":mail"=>$mail, ':status'=>$status]);
	$msg["message"] = "Account created successfully";
}

echo json_encode($msg);