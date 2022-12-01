<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'trans_action';
$data = json_decode(file_get_contents("php://input"), true);
$helper->get_token();
switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            $m = $helper->query("select * from trans_action where t_id=:id", [":id"=>$_GET['id']]);
            $msg["trans"] = $m->fetch(\PDO::FETCH_ASSOC);
        }else{

        }
        break;
    case 'POST':
        // die(json_encode($data));
        $user_id = $helper->get_token()["user_id"];	
        $m_id = $data["member"];
        $trans_type_id = $data["trans_type"];
        // $t_code = $data["code"];
        $t_code = "TRS".time()."CMMF";
        $t_amount = $data["amount"];
        $t_desc = $data["comment"];	
        $w_id = $data['week'];
        $p = $helper->query("select * from trans_action where w_id=:w and m_id=:m and trans_type_id=:id", [":m"=>$m_id, ":w"=>$w_id, ":id"=>$trans_type_id]);
                        
        $helper->required_fields([$m_id, $w_id, $trans_type_id, $t_code, $t_amount]);
        
        
        if($data["amount"]>0){
            
            if($p->rowCount()==0){
                $trans = $helper->query("insert into $tb_name set user_id=:user, w_id=:week, m_id=:member, trans_type_id=:trans, t_code=:code, t_amount=:amount, t_desc=:comment",[":user"=>$user_id,":member"=>$m_id,":trans"=>$trans_type_id,":code"=>$t_code,":amount"=>$t_amount,":comment"=>$t_desc, ":week"=>$w_id]);
            }else{
                $trans = $helper->query("update $tb_name set   t_amount=:amount, t_desc=:comment where w_id=:week and m_id=:member and trans_type_id=:trans",[":member"=>$m_id,":trans"=>$trans_type_id,":amount"=>$t_amount,":comment"=>$t_desc, ":week"=>$w_id]);
            }
            if($trans){
                $msg["status"]= 1;
                $msg["message"] = "Transaction $t_code was successfull...";
                if($trans_type_id==4){
                    $p = $p->fetch(\PDO::FETCH_ASSOC);
                    // die(json_encode($p));
                    $th = $helper->query("select amount from account_balance where m_id=:m", [":m"=>$m_id]);
                    $th = $th->fetch(\PDO::FETCH_ASSOC);

                    $amt = intval($th["amount"]) - intval($p["t_amount"]);
                    // die(json_encode(["message"=>$amt, "amount"=>$th["amount"], "t_amount"=>$p["t_amount"]]));
                    $helper->query("update account_balance set amount=:amt where m_id=:mem",[":mem"=>$m_id, ":amt"=>$amt]);
                    $helper->update_account($m_id, $t_amount, $trans_type_id);
                }
                
            }
            $helper->create_log($helper->get_token()["user_id"], "Transaction {$t_code} amnt {$t_amount} made");
        }else{
            $msg["status"]= 1;
            $msg["message"] = "TRXN $t_code was not processed, amount was insufficient...";
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