<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'guaranter';
$data = json_decode(file_get_contents("php://input"), true);
$helper->get_token();

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

        $m = $helper->query("select * from guaranter_balance where lo_id=:lo and m_id=:m",[":m"=>$m_id, ":lo"=>$lo_id]);
        if($m->rowCount()==1){
            $m = $m->fetch(\PDO::FETCH_ASSOC);
            $amount2 = intval($m["amount"]) + intval($amount);
            $helper->query("update guaranter_balance set amount=:am where lo_id=:lo and m_id=:m",[":lo"=>$lo_id,":m"=>$m_id, ":am"=>$amount2]);
        }else{
            $helper->query("insert into guaranter_balance set lo_id=:lo, m_id=:m, amount=:am",[":lo"=>$lo_id,":m"=>$m_id, ":am"=>$amount]);
        }
        if($he){
            $msg['status']=1;
            $msg['message']='Guaranter was added successful...';
            $newLoan = $helper->get_loan_id($lo_id);
            $accBal = $helper->ledger_sum($newLoan["m_id"], $helper->t_type("saving"));

            $helper->update_account($m_id, $amount, $helper->t_type("loan out"));
            $m = intval($accBal) + intval($helper->guarant_balance($newLoan["lo_id"]));
            $mh = intval($newLoan["lo_amount"]*(1+$newLoan["lo_rate"]/100));
            // $m = intval($m);

            //share
            $x["bal"] = $m;
            $x["loan"] = $mh;
            $x["id"]=$newLoan["m_id"];
            $x["lo_code"] = $newLoan["lo_code"];
            // $helper->create_share($lo_id, $helper->get_last_id("g_id", "guaranter"));
            $helper->write_2_file("../error.txt", json_encode($x));
            if($m>=$mh){
                $helper->query("update loans set ls_id='5' where lo_id=:lo", [":lo"=>$lo_id]);
            }
            $helper->loan_history($amount, $lo_id, 'ADDG');
            $helper->member_history($m_id, 'GRT', json_encode(["member"=>$m_id, "loan"=>$lo_id, "amount"=>$amount]));
        }
        $data["user"]=$helper->get_token()["user_id"];
        $helper->write_2_file("grant.txt", json_encode($data));
        break;
    case 'PUT':

        break;
    case 'DELETE':
        $helper->remove_record("guaranter", "g_id", $_GET['id']);
        $msg["message"] = "Record removed successfully";
        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }
$msg["logged"] = $helper->get_token()["logged"];
echo json_encode($msg);