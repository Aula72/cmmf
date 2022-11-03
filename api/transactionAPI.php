<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'trans_action';
$data = json_decode(file_get_contents("php://input"), true);

switch($meth){
    case 'GET':
        if(isset($_GET['id'])){

        }else{

        }
        break;
    case 'POST':
        // die(json_encode($data));
        $user_id = $helper->get_token()["user_id"];	
        $m_id = $data["member"];
        $trans_type_id = $data["trans_type"];
        $t_code = $data["code"];
        $t_amount = $data["amount"];
        $t_desc = $data["comment"];	
        $helper->required_fields([$m_id, $trans_type_id, $t_code, $t_amount]);
        $trans = $helper->query("insert into $tb_name set user_id=:user, m_id=:member, trans_type_id=:trans, t_code=:code, t_amount=:amount, t_desc=:comment",[":user"=>$user_id,":member"=>$m_id,":trans"=>$trans_type_id,":code"=>$t_code,":amount"=>$t_amount,":comment"=>$t_desc]);
        if($trans){
            $msg["status"]= 1;
            $msg["message"] = "Transaction $t_code was successfull...";
            $helper->update_account($m_id, $t_amount, $trans_type_id);
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