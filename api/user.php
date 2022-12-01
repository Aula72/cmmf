<?php 
header("Content-Type: application/json");
// echo json_encode(["message"=>"Api is accessible"]);

include_once "../controllers/Helpers.php";

$cn = 100;
if(isset($_GET["num"])){
	$cn = intval($_GET["num"]);
}
$help = new \Cmmf\Helper;
$user = $help->query("select * from user order by user_id desc limit {$cn}");
$groups = $help->query("select * from grouping order by g_id desc limit {$cn}");
$ledger = $help->query("select * from trans_types order by ty_id desc limit {$cn}");
$tokens = $help->query("select * from tokens order by id desc limit {$cn}");
$otps = $help->query("select * from otp order by id desc limit {$cn}");
$weeks = $help->query("select * from weeks order by w_id desc limit {$cn}");
$loans = $help->query("select * from loans order by lo_id desc limit {$cn}");
$members = $help->query("select * from group_member order by m_id desc limit {$cn}");
$trans = $help->query("select * from trans_action order by t_id desc limit {$cn}");
$logs = $help->query("select * from user_logs order by log_id desc limit {$cn}");

//docs

$u["logs"] = [];

foreach($logs->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["logs"], $row);
}

$u["trans"] = [];

foreach($trans->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["trans"], $row);
}
$u["users"] = [];

foreach($user->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["users"], $row);
}

$u["members"] = [];

foreach($members->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["members"], $row);
}

$u["groups"] = [];

foreach($groups->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["groups"], $row);
}

$u["ledger"] = [];

foreach($ledger->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["ledger"], $row);
}
$u["otps"] = [];

foreach($otps->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["otps"], Array(
		"id"=>$row["id"],"user_id"=>$row["user_id"],"created_at"=>$row["created_at"],"expiry"=>$row["expiry"]));
}
$u["tokens"] = [];

foreach($tokens->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["tokens"], Array(
		"id"=>$row["id"],"user_id"=>$row["user_id"],"created_at"=>$row["created_at"]));
}

$u["weeks"] = [];

foreach($weeks->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["weeks"], $row);
}

$u["loans"] = [];

foreach($loans->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["loans"], $row);
}

if(isset($_GET["type"])){
	echo json_encode($u[$_GET['type']]?[$_GET['type']=>$u[$_GET['type']]]:["message"=>"{$_GET['type']} Is not availed option, available options are tokens, users, opts, trans, loans, weeks, members, groups"]);
}else{
	echo json_encode($u);
}