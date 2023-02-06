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
        $q = $helper->query("select * from trans_action where w_id=:w and m_id=:m and trans_type_id=:id and t_amount=:amnt", [":m"=>$m_id, ":w"=>$w_id, ":id"=>$trans_type_id, ":amnt"=>$t_amount]);
        $q = $q->fetch();
        if($q){
            die(json_encode(["status"=>1, "message"=>"Transaction already exists...."]));
        }
        $helper->write_2_file("../logs/trans.txt", json_encode($data));
        $helper->required_fields([$m_id, $w_id, $trans_type_id, $t_code, $t_amount]);
        $helper->deposit_to_ledger(["m_id"=>$m_id, "trans_type_id"=>$trans_type_id, "w_id"=>$w_id,"amount"=>$t_amount, "t_code"=>$t_code, "t_desc"=>$t_desc]);
        if($trans_type_id == $helper->t_type("saving")){
            
                $loan = $helper->query("select * from loans where m_id=:m and ls_id=:l",[":m"=>$m_id, ":l"=>'2']);
                $loan = $loan->fetch(\PDO::FETCH_ASSOC);
                // $helper->write_2_file("../error.txt",json_encode(["loan"=>$loan, "m_id"=>$m_id]));
                if($loan){
                    $loan_bal = $helper->loan_balance($loan["lo_id"]);
                    $helper->write_2_file("../error.txt",json_encode(["balance"=>$loan_bal,]));
                    $p_comment = "Loan automatic paid on saving";
                    if($loan_bal>=$t_amount){
                        $amount = $t_amount;
                    }else{
                        $amount = $loan_bal;
                    }
                    $helper->query("insert loan_payment set lo_id=:lo, amount=:am, p_comment=:co, user_id=:us, trans_id=:t",[":lo"=>$loan["lo_id"], ":am"=>$amount, ':co'=>$p_comment, ":t"=>$t_code, ":us"=>$user_id]);
                    $guaranters = $helper->query("select * from guaranter where lo_id=:lo",[":lo"=>$loan["lo_id"]]);
                    $guaranter = $helper->query("select * from guaranter where lo_id=:lo",[":lo"=>$loan["lo_id"]]);
                    $x = 0;
                    $arr = [];
                    foreach($guaranters->fetchAll(\PDO::FETCH_ASSOC) as $ty){
                        array_push($arr, $helper->guaranter_percentage($ty["m_id"], $loan["lo_id"]));
                    }
                    
                    $i = 0;
                    // $m = 0;
                    foreach($guaranter->fetchAll(\PDO::FETCH_ASSOC) as $row){
                        $p = $arr[$i]*$amount;
                        
                        $tym = $helper->query("select * from guaranter_balance where lo_id=:lo and m_id=:m",[":lo"=>$loan["lo_id"], ":m"=>$row["m_id"]]);
                        $tym = $tym->fetch(\PDO::FETCH_ASSOC);
                        $amn = $tym["amount"] - $p;
                        // $helper->write_2_file("../error.txt", $amn);
                        if($amn>=0){
                            $helper->query("update guaranter_balance set amount=:am where lo_id=:lo and m_id=:m",[":lo"=>$loan["lo_id"], ":m"=>$row["m_id"], ":am"=>$amn]);
                        }else{
                            $helper->query("update guaranter_balance set amount=:am where lo_id=:lo and m_id=:m",[":lo"=>$loan["lo_id"], ":m"=>$row["m_id"], ":am"=>0]);
                        }
                        $m += $p;
                    }

                    if($helper->loan_balance($loan["lo_id"])==0){
                        $helper->query("update loans set ls_id=:uo where lo_id=:lo",[":uo"=>4, ":lo"=>$loan["lo_id"]]);
                        $helper->loanable_member($m_id, 1);
                    }
                }
        
        
            // }
        }
        //     else{
        //         $helper->deposit_to_ledger(["m_id"=>$m_id, "trans_type_id"=>$trans_type_id, "w_id"=>$w_id,"amount"=>$t_amount, "t_code"=>$t_code, "t_desc"=>$t_desc]);
        //     }
        // }else{
        //     $helper->deposit_to_ledger(["m_id"=>$m_id, "trans_type_id"=>$trans_type_id, "w_id"=>$w_id,"amount"=>$t_amount, "t_code"=>$t_code, "t_desc"=>$t_desc]);
        // }

        $msg["status"] = 1;
        $msg["message"] = "Transaction TXNID $t_code was successful...";
        /*
        $tu = $helper->deposit_to_ledger(["m_id"=>$m_id, "trans_type_id"=>$trans_type_id, "w_id"=>$w_id,"amount"=>$t_amount, "t_code"=>$t_code, "t_desc"=>$t_desc]);
        
        if($tu){
            $msg["status"]= 1;
            $msg["message"] = "Transaction $t_code was successfull...";
            if($trans_type_id==$helper->t_type("saving") && $helper->member_has_loan($m_id)){
                
                   
                    $loan = $helper->my_loan($m_id);
                    $guaranters = $helper->query("select * from guaranter where lo_id=:lo",[":lo"=>$loan]);
                    $x = 0;
                    foreach($guaranters->fetchAll(\PDO::FETCH_ASSOC) as $row){

                        $p = $helper->guaranter_percentage($row["m_id"], $loan)*$t_amount;
                        $x += $p;
                        $helper->pay_guaranter($row["lo_id"], $row["m_id"],$p);
                        $helper->deposit_to_ledger(["m_id"=>$m_id, "trans_type_id"=>$trans_type_id, "w_id"=>$w_id,"amount"=>-1*$p, "t_code"=>$t_code, "t_desc"=>$t_desc]);
                    }
                    $rt8 = $t_amount - $x;
                    $helper->update_account($m_id, $rt8, $helper->t_type("saving"));
                
            }else{
                $helper->update_account($m_id, $t_amount, 1);
            }
        }else{
            $msg["status"]= 1;
            $msg["message"] = "TRXN $t_code was not processed, amount was insufficient...";
        }
        */
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