<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";

$help = new \Cmmf\Helper;

$data = json_decode(file_get_contents("php://input"), true);

$uname = $data["uname"];
// die(json_encode($data));
$help->req_method('post');

$check = $help->query("select * from user where mail=:uname",[":uname"=>$uname]);


if($check->rowCount()>0){
	$user = $check->fetch(\PDO::FETCH_ASSOC);
	// $otp = rand(10000,99999);
	if($user['status']==1){
		$otp = rand(0,9).rand(0,9).rand(0,9).rand(0,9).rand(0,9);
		$msg["status"] = 1;
		$msg["message"] = "Login was successful an OTP was sent to your email address";
		$msg["otp"] = $otp;
		$msg["full_name"] = $user["fname"][0].". ".$user["lname"];
		$msg["long_name"] = $user["fname"]." ".$user["lname"];
		$msg["utype"]  = $user["user_type_id"];
		// mail(OTP_MAIL, $grt, $msg, $headers);
		$help->mail_send($otp, $uname);
		$otp = password_hash($otp, PASSWORD_DEFAULT);
		$check_token = $help->query("select * from otp where user_id=:uid",[":uid"=>$user["user_id"]]);
		if($check_token->rowCount()>0){
			$help->query("update otp set otp=:otp, expiry=DATE_ADD(NOW(), INTERVAL 1 DAY) where user_id=:user",[":otp"=>$otp,":user"=>$user["user_id"]]);
		}else{
			$help->query("insert into otp set otp=:otp, user_id=:user, expiry=DATE_ADD(NOW(), INTERVAL 1 DAY)",[":otp"=>$otp,":user"=>$user["user_id"]]);
		}
		$help->create_log($user["user_id"], "Token sent");
	}else{
		$msg["status"] = 0;
		$msg["message"] = "Sorry account is not active, please contact general admin to help on this issue";
	}
}else{
	$msg["status"] = 0;
	$msg["message"] = "Your have no account with CMMF, please register";
}

echo json_encode($msg);