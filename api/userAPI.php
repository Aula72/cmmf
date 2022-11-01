<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'user';
$data = json_decode(file_get_contents("php://input"), true);

switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            $id = $_GET["id"];
            $user = $helper->query("select * from $tb_name where user_id=:id",[":id"=>$id]);
            if($user->rowCount()>0){
                $msg["status"] = 1;
                $msg["admin"] = $user->fetch(PDO::FETCH_ASSOC);
            }else{
                $msg["message"] = "No such user";
                $msg["status"] = 0;
            }
        }else{
            $user = $helper->query("select * from $tb_name");
            $msg["admin"] = [];
            foreach($user->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg['admin'], $row);
            }
        }
        break;
    case 'POST':
        $mail = $data["mail"];
        $fname = $data["fname"];
        $lname = $data["lname"];
        $status = 0;
        $helper->required_fields([$mail, $fname, $lname]);
        $helper->get_token();
        $user = $helper->query("insert into $tb_name set mail=:mail, fname=:fname, lname=:lname, status=:status",[":mail"=>$mail, ":fname"=>$fname, ":lname"=>$lname, ":status"=>$status]);
        if($user){
            $msg["status"] = 1;
            $msg["message"] = "User was added successfully...";
        }
        break;
    case 'PUT':
        $id = $_GET["id"];
        $mail = $data["mail"];
        $fname = $data["fname"];
        $lname = $data["lname"];
        $status = $data["status"];
        $helper->required_fields([$mail, $fname, $lname]);
        $helper->get_token();
        $user = $helper->query("update $tb_name set mail=:mail, fname=:fname, lname=:lname, status=:status where user_id=:id",[":mail"=>$mail, ":fname"=>$fname, ":lname"=>$lname, ":status"=>$status]);
        if($user){
            $msg["status"] = 1;
            $msg["message"] = "User was updated successfully...";
        }
        break;
    case 'DELETE':

        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }

echo json_encode($msg);