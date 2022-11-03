<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'guaranter';
$data = json_decode(file_get_contents("php://input"), true);

switch($meth){
    case 'GET':
        if(isset($_GET['id'])){

        }else{

        }
        break;
    case 'POST':
        $m_id = $data['member'];
        $lo_id = $data['loan'];
        $amount = $data['amount'];
        $user_id = $helper->get_token()['user_id'];
        $helper->required_fields([$m_id, $lo_id, $amount, $user_id]);
        $he = $helper->query("insert into $tb_name set m_id=:member, lo_id=:loan, amount=:amount, user_id=:user",[":member"=>$m_id,":loan"=>$lo_id, ':amount'=>$amount,':user'=>$user_id]);
        if($he){
            $msg['status']=1;
            $msg['message']='Guaranter was added successful...';
            $helper->update_account($m_id, $amount, 7);
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