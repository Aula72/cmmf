<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'loans';
$data = json_decode(file_get_contents("php://input"), true);
$helper->get_token();
switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            $tra = $helper->query("select * from $tb_name where lo_id=:id or lo_code=:id",[":id"=>$_GET['id']]);
            if($tra->rowCount()>0){
                $msg["loan"] = $tra->fetch(\PDO::FETCH_ASSOC);
                $msg["balance"] = $helper->loan_balance($msg["loan"]["lo_id"])==null?(1+intval($msg["loan"]["lo_rate"])/100)*$msg["loan"]["lo_amount"]:$helper->loan_balance($msg["loan"]["lo_id"]);
                $msg["member"] = $helper->get_member($msg["loan"]["m_id"]);
                $msg["status"] = $msg["loan"]["ls_id"];
                $msg["status_name"] = ucfirst($helper->loan_status($msg["loan"]["ls_id"]));
                $gra = $helper->query("select * from guaranter where lo_id=:id",[":id"=>$msg['loan']['lo_id']]);
                $msg["guaranters"] = [];
                foreach($gra->fetchAll(\PDO::FETCH_ASSOC) as $row){
                    $r =$helper->get_member($row["m_id"]);
                    array_push($msg['guaranters'], array(
                        "g_id"=>$row["g_id"],
                        "m_id"=>$row["m_id"],
                        "lo_id"=>$row["lo_id"],
                        "amount"=>number_format($row["amount"]),
                        "user_id"=>$row["user_id"],
                        "name"=> $r['m_fname']." ".$r['m_lname']
                    ));
                }
                $msg["payments"]  = [];
                $x = $msg["loan"]["lo_id"];
                $m = $helper->query("select * from loan_payment where lo_id=:lo",[":lo"=>$x]);
                foreach($m->fetchAll(\PDO::FETCH_ASSOC) as $row){
                    array_push($msg["payments"], $row);
                }
                $msg["fines"] = [];
            }else{
                $msg["loan"] = "No such loan";
                $msg["status"] = 0;
            }
        }else if(isset($_GET["sbal"])){
            $u = $helper->query("select sum(amount) as amt from guaranter where m_id=:id",[":id"=>$_GET['sbal']]);
            $u = $u->fetch(\PDO::FETCH_ASSOC);

            // $p = $helper->query("select sum(amount) as amt from account_balance where m_id=:id",[":id"=>$_GET['sbal']]);
            // $p = $p->fetch(\PDO::FETCH_ASSOC);
            $p = $helper->ledger_sum($_GET["sbal"],$helper->t_type("saving"));

            $msg["message"] = intval($p) - intval($u["amt"]);
            $msg["message"] = $helper->new_worth($_GET["sbal"]);
        }else{
            $loans = $helper->query("select * from loans order by lo_id desc");
            $msg["loans"] = [];
            foreach($loans->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg["loans"], [
                    "lo_id"=>$row["lo_id"],
                    "lo_code"=>$row["lo_code"],
                    "lo_rate"=>$row["lo_rate"],
                    "m_id"=>$row["m_id"],
                    "user_id"=>$row["user_id"],
                    "lo_amount"=>$row["lo_amount"],
                    "created_at"=>$row["created_at"],
                    "updated_at"=>$row["updated_at"],
                    "ls_id"=>$row["ls_id"],
                    "lo_expiry"=>$row["lo_expiry"],
                    "balance"=>$helper->loan_balance($row["lo_id"]),
                    "member"=>$helper->get_member($row["m_id"])["m_code"]
                    // "balance"=>$helper->loan_balance($row["lo_id"])==null?(1+intval($row["lo_rate"])/100)*$row["lo_amount"]:$helper->loan_balance($row["lo_id"]),
                ]);
            }
        }
        break;
    case 'POST':
        $lo_code = $data["code"];
        $lo_rate = $data["rate"];
        $lo_expiry = $data["expiry"];
        $m_id = $data["member"];
        $user_id = $helper->get_token()["user_id"];	
        $lo_amount = $data["amount"];
        $st = 1;

        $lp = intval($lo_amount*(1+intval($lo_rate)/100));
        if($lp<$helper->new_worth($m_id)){
            $st = 5;
        }
        $helper->required_fields([$lo_code, $lo_amount, $lo_expiry,$lo_rate]);
        $helper->write_2_file("loans.txt", json_encode($data));
        $loan  =  $helper->query("insert into $tb_name set 	lo_code=:code,	lo_rate=:rate, lo_expiry=:expiry,m_id=:member,user_id=:user,lo_amount=:amount, ls_id=:status",[":status"=>$st,":code"=>$lo_code,":rate"=>$lo_rate,":member"=>$m_id,":amount"=>$lo_amount, ":user"=>$user_id, ":expiry"=>$lo_expiry]);
        if($loan){
            $msg["status"]=1;
            $msg["message"] = "Loan $lo_code was created successfully...";
        }
        // $helper->update_account($m_id, (1+$lo_rate/100)*$lo_amount, -1);
        $helper->loanable_member($m_id, 0);
        $helper->loan_history($lo_amount, $helper->get_last_id("lo_id","loans"),"CRT");
        break;
    case 'PUT':
        if(isset($_GET["approve-loan"])){
            $rtyu = $_GET["approve-loan"];
            $helper->query("update $tb_name set ls_id=:lsd where lo_id=:id",[":id"=>$helper->get_loan_id($_GET["approve-loan"])["lo_id"],":lsd"=>2]);
            $msg["status"]=1;
            $msg["message"] = "Loan $rtyu approved successfully";
            // $msg["message"] = $helper->get_loan_id($_GET["approve-loan"])["lo_id"];
        }
        break;
    case 'DELETE':
        $helper->remove_record("loans", "lo_id", $_GET['id']);
        $helper->remove_record("loan_history", "lo_id", $_GET['id']);
        $helper->remove_record("loan_payment", "lo_id", $_GET['id']);
        $msg["message"] = "Record removed successfully";
        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }

echo json_encode($msg);