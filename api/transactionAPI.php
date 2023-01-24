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
        $t_code = $helper->t_id();
        $t_amount = $data["amount"];
        $t_desc = $data["comment"];	
        $w_id = $data['week'];
        $p = $helper->query("select * from trans_action where w_id=:w and m_id=:m and trans_type_id=:id", [":m"=>$m_id, ":w"=>$w_id, ":id"=>$trans_type_id]);
                        
        $helper->required_fields([$m_id, $w_id, $trans_type_id, $t_code, $t_amount]);
        

        $tu = $helper->deposit_to_ledger(["m_id"=>$m_id, "trans_type_id"=>$trans_type_id, "w_id"=>$w_id,"amount"=>$t_amount, "t_code"=>$t_code, "t_desc"=>$t_desc]);
        // die(json_encode($data));
        // die(json_encode(["tur"=>$tu]));
        if($tu){
            $msg["status"]= 1;
            $msg["message"] = "Transaction $t_code was successfull...";
            if($trans_type_id==$helper->t_type("saving") && $helper->member_has_loan($m_id)){
                
                   
                    $loan = $helper->my_loan($m_id);
                    $guaranters = $helper->query("select * from guaranter where lo_id=:lo",[":lo"=>$loan]);
                    foreach($guaranters->fetchAll(\PDO::FETCH_ASSOC) as $row){
                        $p = $helper->guaranter_percentage($row["m_id"], $loan)*$t_amount;
                        $helper->pay_guaranter($row["lo_id"], $row["m_id"],$t_amount*$p);
                        $helper->deposit_to_ledger(["m_id"=>$m_id, "trans_type_id"=>$trans_type_id, "w_id"=>$w_id,"amount"=>-1*$p*$t_amount, "t_code"=>$t_code, "t_desc"=>$t_desc]);
                    }

                
            }
        }else{
            $msg["status"]= 1;
            $msg["message"] = "TRXN $t_code was not processed, amount was insufficient...";
        }
        // die(json_encode($data));
        // die(json_encode(["id"=>3]));
        // if($trans_type_id==1){
        //     if($helper->member_has_loan($m_id)){
        //         // if($helper->guarant_balance())
        //     }else{

        //     }
        // }else{

        // }
        // if($data["amount"]>0){
            
        //     if($p->rowCount()==0){
        //         $trans = $helper->query("insert into $tb_name set user_id=:user, w_id=:week, m_id=:member, trans_type_id=:trans, t_code=:code, t_amount=:amount, t_desc=:comment",[":user"=>$user_id,":member"=>$m_id,":trans"=>$trans_type_id,":code"=>$t_code,":amount"=>$t_amount,":comment"=>$t_desc, ":week"=>$w_id]);
        //     }else{
        //         $trans = $helper->query("update $tb_name set   t_amount=:amount, t_desc=:comment where w_id=:week and m_id=:member and trans_type_id=:trans",[":member"=>$m_id,":trans"=>$trans_type_id,":amount"=>$t_amount,":comment"=>$t_desc, ":week"=>$w_id]);
        //     }
        //     if($trans){
        //         $msg["status"]= 1;
        //         $msg["message"] = "Transaction $t_code was successfull...";
        //         if($trans_type_id==1){
        //             $p = $p->fetch(\PDO::FETCH_ASSOC);
        //             // die(json_encode($p));
        //             $th = $helper->query("select amount from account_balance where m_id=:m", [":m"=>$m_id]);
        //             $th = $th->fetch(\PDO::FETCH_ASSOC);

        //             $amt = intval($th["amount"]) - intval($p["t_amount"]);
        //             // die(json_encode(["message"=>$amt, "amount"=>$th["amount"], "t_amount"=>$p["t_amount"]]));
        //             $helper->query("update account_balance set amount=:amt where m_id=:mem",[":mem"=>$m_id, ":amt"=>$amt]);
        //             $helper->update_account($m_id, $t_amount, $trans_type_id);
        //         }
                
        //     }
        //     $helper->create_log($helper->get_token()["user_id"], "Transaction {$t_code} amnt {$t_amount} made");
        // }else{
        //     $msg["status"]= 1;
        //     $msg["message"] = "TRXN $t_code was not processed, amount was insufficient...";
        // }
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