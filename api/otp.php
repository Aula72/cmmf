<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";

$help = new \Cmmf\Helper;

$data = json_decode(file_get_contents("php://input"), true);

$otp = $data["otp"];
$uname = $data["mail"];
// die(json_encode($data));
$help->req_method('post');

$check = $help->query("select * from user where mail=:uname",[":uname"=>$uname]);


if($check->rowCount()>0){
	$user = $check->fetch(\PDO::FETCH_ASSOC);
	$msg["mail"] = $user["mail"];
	$id = $user["user_id"];

	$check_2 = $help->query("select * from otp where user_id=:id",[":id"=>$id]);
	$u = $check_2->fetch(\PDO::FETCH_ASSOC);
	if($check_2->rowCount()>0){
		if(password_verify($otp, $u['otp'])){
			$msg["status"] = 1;
			$msg["message"] = "OTP verified successfully";
			$msg["token"] = $help->create_token($id);
		}else{
			$msg["status"] = 0;
			$msg["message"] = "Wrong OTP try again";
		}
	}else{
		$msg["status"] = 0;
		$msg["message"] = "Enter your email to login";
	}
	
	
	
	
}else{
	$msg["status"] = 0;
	$msg["message"] = "Wrong OTP try again";
}

echo json_encode($msg);