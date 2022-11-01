<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'legder';
$data = json_decode(file_get_contents("php://input"), true);

switch($meth){
    case 'GET':
        if(isset($_GET['id'])){

        }else{

        }
        break;
    case 'POST':
        $user_id = $helper->get_token()['user_id'];
        $m_id = $data["member"];	
        $amount = $data['amount'];	
        $l_type = $data["ltype"];
        $trans = uniqid();
        // die(json_encode($data));
        $helper->required_fields([$m_id, $amount, $l_type]);
        $r = $helper->query("insert into $tb_name set user_id=:user, m_id=:m, amount=:a, l_type=:l, trans_id=:t",[":user"=>$user_id,":m"=>$m_id, ":a"=>$amount,":l"=>$l_type, ':t'=>$trans]);
        if($r){
            $msg["status"] = 1;
            $msg["message"] = "Transaction $trans was made successfully!";
        }
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