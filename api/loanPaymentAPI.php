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
        $trans_id = $helper->t_id()."N";	
        $loan = $data["loan"];	
        $amount = $data["amount"];	
        $p_comment = $data["comment"];	
        $user_id = $helper->get_token()["user_id"];

        $w_id = $helper->my_current_week($helper->get_loan_id($loan)["m_id"]);

        $m_id = $helper->get_loan_id($loan)["m_id"];

        $helper->query("insert loan_payment set lo_id=:lo, amount=:am, p_comment=:co, user_id=:us, trans_id=:t",[":lo"=>$loan, ":am"=>$amount, ':co'=>$p_comment, ":t"=>$trans_id, ":us"=>$user_id]);
        // $po["loan_bal"] = $helper->loan_balance($loan);
        $guaranters = $helper->query("select * from guaranter where lo_id=:lo",[":lo"=>$loan]);
        $guaranter = $helper->query("select * from guaranter where lo_id=:lo",[":lo"=>$loan]);
        $x = 0;
        $arr = [];
        foreach($guaranters->fetchAll(\PDO::FETCH_ASSOC) as $ty){
            array_push($arr, $helper->guaranter_percentage($ty["m_id"], $loan));
        }
        
        $i = 0;
            
        foreach($guaranter->fetchAll(\PDO::FETCH_ASSOC) as $row){
            $p = $arr[$i]*$amount;
            
            $tym = $helper->query("select * from guaranter_balance where lo_id=:lo and m_id=:m",[":lo"=>$loan, ":m"=>$row["m_id"]]);
            $tym = $tym->fetch(\PDO::FETCH_ASSOC);
            $amn = $tym["amount"] - $p;
            $helper->write_2_file("../error.txt", $amn);
            if($amn>=0){
                $helper->query("update guaranter_balance set amount=:am where lo_id=:lo and m_id=:m",[":lo"=>$loan, ":m"=>$row["m_id"], ":am"=>$amn]);
            }else{
                $helper->query("update guaranter_balance set amount=:am where lo_id=:lo and m_id=:m",[":lo"=>$loan, ":m"=>$row["m_id"], ":am"=>0]);
            }
        }

        if($helper->loan_balance($loan)==0){
            $helper->query("update loans set ls_id=:uo where lo_id=:lo",[":uo"=>4, ":lo"=>$loan]);
            $helper->loanable_member($m_id, 1);
        }
        
        $msg["status"] = 1;
        $msg["message"] = "Loan payment TXNID $trans_id was successful...";
        
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