<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = '';
$data = json_decode(file_get_contents("php://input"), true);

switch($meth){
    case 'GET':
        $helper->get_token();
        $msg['status'] = 1;
        break;
    case 'POST':
        $helper->get_token();
        $msg['status'] = 1;
        break;
    case 'PUT':

        break;
    case 'DELETE':

        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }

echo json_encode($msg);