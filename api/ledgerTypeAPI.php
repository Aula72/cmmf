<?php
header("Content-Type: application/json"); 
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'trans_types';
$data = json_decode(file_get_contents("php://input"), true);

switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            $tra = $helper->query("select * from $tb_name where ty_id=:id",[":id"=>$_GET['id']]);
            if($tra->rowCount()>0){
                $msg["ledge_type"] = $tra->fetch(\PDO::FETCH_ASSOC);
                $msg["status"] = 1;
            }else{
                $msg["ledge_type"] = "No such ledger";
                $msg["status"] = 0;
            }
        }else{
            $tra = $helper->query("select * from $tb_name");
            $msg["ledger_type"] = [];
            foreach($tra->fetchAll(\PDO::FETCH_ASSOC) as $row){
                array_push($msg['ledger_type'], $row);
            }
        }
        break;
    case 'POST':
        $helper->get_token();
        $ty_name = $data['name'];	
        $mult = $data['mult'];
        $helper->required_fields([$mult, $ty_name]);
        $j = $helper->query("insert into $tb_name set ty_name=:name, mult=:m",[":m"=>$mult,":name"=>$ty_name]);
        if($j){
            $msg["status"] = 1;
            $msg["message"] = "Ledger type was added successfully";
        }
        break;
    case 'PUT':
        $id = isset($_GET['id'])?$_GET['id']:die(json_encode(["error"=>"No record to update"]));
        $helper->get_token();
        $ty_name = $data['name'];	
        $mult = $data['mult'];
        $helper->required_fields([$mult, $ty_name]);
        $j = $helper->query("update $tb_name set ty_name=:name, mult=:m where ty_id=:id",[":m"=>$mult,":name"=>$ty_name, ":id"=>$id]);
        if($j){
            $msg["status"] = 1;
            $msg["message"] = "Ledger type was updated successfully";
        }
        break;
    case 'DELETE':

        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }

echo json_encode($msg);