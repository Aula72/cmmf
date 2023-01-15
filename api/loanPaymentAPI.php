<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'loan_payment';
$data = json_decode(file_get_contents("php://input"), true);
$helper->get_token();
switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            $t  = $helper->query("select * from $tb_name where p_id=:p", [":p"=>$_GET['id']]);
            $t = $t->fetch(\PDO::FETCH_ASSOC);

            $msg["trans"] = $t;
        }else{

        }
        break;
    case 'POST':
        $trans_id = $helper->t_id();	
        $lo_id = $data["loan"];	
        $amount = $data["amount"];	
        $p_comment = $data["comment"];	
        $user_id = $helper->get_token()["user_id"];

        $w_id = 100;
        //guaranters
        $gua = $helper->query("select * from guaranter_balance where lo_id=:lo", [":lo"=>$lo_id]);
        if($gua->rowCount()>0){
            $guaranters = $helper->query("select * from guaranter where lo_id=:lo",[":lo"=>$lo_id]);
            foreach($guaranters->fetchAll(\PDO::FETCH_ASSOC) as $row){
                // die(json_encode($row));
                
                $p = $helper->guaranter_percentage($row["m_id"], $lo_id);
                // die(json_encode(["id"=>$p]));
                $helper->pay_guaranter($lo_id, $row["m_id"],$amount*$p);
                $helper->deposit_to_ledger(["m_id"=>$row["m_id"], "trans_type_id"=>$helper->t_type("saving"), "w_id"=>$w_id,"amount"=>-1*$p*$amount, "t_code"=>$helper->t_id(), "t_desc"=>$p_comment]);
            }
            // die(json_encode($tp));
            $msg["status"] = 1;
            $msg["message"] = "Loan payment with, transaction ID $trans_id was successfull...";
        }else{
        $rt = $helper->query("insert into $tb_name set trans_id=:t, lo_id=:lo, amount=:a, p_comment=:c, user_id=:u", [":t"=>$trans_id, ":lo"=>$lo_id, ":a"=>$amount, ":c"=>$p_comment, ":u"=>$user_id]);
        if($rt){
            $msg["status"] = 1;
            $msg["message"] = "Loan payment with, transaction ID $trans_id was successfull...";
            $loan = $helper->query("select * from loans where lo_id=:lo", [":lo"=>$lo_id]);
            $loan  = $loan->fetch(\PDO::FETCH_ASSOC);
            $y = $helper->query("select sum(amount) as amt from loan_payment where lo_id=:id",[":id"=>$lo_id]);
            $y = $y->fetch(\PDO::FETCH_ASSOC);
            $due = intval($loan["lo_amount"])*(1+intval($loan["lo_rate"])/100) - intval($y["amt"]);
            if($due==0){
                $helper->query("update loans set ls_id=:ls where lo_id=:lo",[":lo"=>$lo_id, ":ls"=>4]);
                $helper->loanable_member($loan["m_id"], 1);
            }
            // $msg["message"]  = $due;
            // if(intval())
            $helper->loan_history($amount, $lo_id, 'PYT');
            $helper->create_log($user_id, "Loan {$loan['lo_code']} amt {$amount} paid by {$user_id}");
        }
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