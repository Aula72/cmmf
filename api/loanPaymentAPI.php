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
        $trans_id = "TRN".time()."CMMF";	
        $lo_id = $data["loan"];	
        $amount = $data["amount"];	
        $p_comment = $data["comment"];	
        $user_id = $helper->get_token()["user_id"];

        
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
            }
            // $msg["message"]  = $due;
            // if(intval())
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