<?php 
header("Content-Type: application/json");
// echo json_encode(["message"=>"Api is accessible"]);

include_once "../controllers/Helpers.php";

$help = new \Cmmf\Helper;
$user = $help->query("select * from user");

$u = [];

foreach($user->fetchAll(\PDO::FETCH_ASSOC) as $row){
	array_push($u, $row);
}

echo json_encode($u);