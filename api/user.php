<?php 
header("Content-Type: application/json");
// echo json_encode(["message"=>"Api is accessible"]);

include_once "../controllers/Helpers.php";

$help = new \Cmmf\Helper;
$user = $help->query("select * from user");
$groups = $help->query("select * from grouping");
$ledger = $help->query("select * from trans_types");
$tokens = $help->query("select * from tokens");
$weeks = $help->query("select * from weeks");
$loans = $help->query("select * from loans");
$members = $help->query("select * from group_member");
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
$u["tokens"] = [];

foreach($tokens->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["tokens"], $row);
}

$u["weeks"] = [];

foreach($weeks->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["weeks"], $row);
}

$u["loans"] = [];

foreach($loans->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u["loans"], $row);
}

echo json_encode($u);