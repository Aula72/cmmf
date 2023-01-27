<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = '';
$data = json_decode(file_get_contents("php://input"), true);
// $helper->get_token();
switch($meth){
    case 'GET':
        $msg["reports"]  = [];
        if(isset($_GET['id'])){
            $y = $helper->query("SELECT  m_id, m_code, m_fname, m_lname, m_gender, m_dob, m_phone, 
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=4 and trans_action.m_id=group_member.m_id),0) as savings, 
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=5 and trans_action.m_id=group_member.m_id),0) as sfund,
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=6 and trans_action.m_id=group_member.m_id),0) as fine, 
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=7 and trans_action.m_id=group_member.m_id),0) as edu_in, 
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=8 and trans_action.m_id=group_member.m_id),0) as edu_out,
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=9 and trans_action.m_id=group_member.m_id),0) as subscription,
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=10 and trans_action.m_id=group_member.m_id),0) as repayment,
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=11 and trans_action.m_id=group_member.m_id),0) as loan_out,
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=12 and trans_action.m_id=group_member.m_id),0) as  loan_charges, 
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=13 and trans_action.m_id=group_member.m_id),0) as loan_form,
            ifnull((select sum(t_amount) from trans_action where trans_action.trans_type_id=14 and trans_action.m_id=group_member.m_id),0) as membership 
            FROM group_member WHERE g_id=:id", [":id"=>$_GET["id"]]);

            
            foreach($y->fetchAll(\PDO::FETCH_ASSOC) as $row){
                array_push($msg["reports"], $row);
            }
        }else if(isset($_GET["loans"])){
            if(isset($_GET["days"])){

            }else if(isset($_GET["status"])){
                $loan = $helper->query("select * from loans where ls_id=:lo",[":lo"=>$_GET["status"]]);
            }else{
                $loan = $helper->query("select * from loans");
            } 
            foreach($loan->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg["reports"], [
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
                    "to_pay"=>(1+$row["lo_rate"]/100)*$row["lo_amount"],
                    "balance"=>$helper->loan_balance($row["lo_id"]),
                    "member"=>$helper->get_member($row["m_id"])["m_code"]
                    // "balance"=>$helper->loan_balance($row["lo_id"])==null?(1+intval($row["lo_rate"])/100)*$row["lo_amount"]:$helper->loan_balance($row["lo_id"]),
                ]);
            }
        }else if(isset($_GET["group"])){
            // if(isset($_GET["week"])){
            //     $mem = $helper->query("select * from group_member where g_id=:g",[":g"=>$_GET["group"]]); 
            // }
            $mem = $helper->query("select * from group_member where g_id=:g",[":g"=>$_GET["group"]]);
            // $msg["total_saving"] = 0;
            foreach($mem->fetchAll(\PDO::FETCH_ASSOC) as $row){
                array_push($msg["reports"], [
                    "m_id"=>$row["m_id"],
                    "m_code"=>$row["m_code"],
                    "g_id"=>$row["g_id"],
                    "user_id"=>$row["user_id"],
                    "m_fname"=>$row["m_fname"],
                    "m_lname"=>$row["m_lname"],
                    "m_phone"=>$row["m_phone"],
                    "m_nin"=>$row["m_nin"],
                    "m_gender"=>$row["m_gender"],
                    "m_dob"=>$row["m_dob"],
                    "created_at"=>$row["created_at"],
                    "update_at"=>$row["update_at"],
                    "saving"=>$helper->ledger_sum($row["m_id"], $helper->t_type("saving")) - $helper->get_guarantee_balance($row["m_id"]),
                    "social_fund"=>$helper->ledger_sum($row["m_id"], $helper->t_type("social fund")),
                    "fine"=>$helper->ledger_sum($row["m_id"], $helper->t_type("fine")),
                    "education_in"=>$helper->ledger_sum($row["m_id"], $helper->t_type("education in")),
                    "education_out"=>$helper->ledger_sum($row["m_id"], $helper->t_type("education out")),
                    "subscription"=>$helper->ledger_sum($row["m_id"], $helper->t_type("subscription")),
                    "repayment"=>$helper->ledger_sum($row["m_id"], $helper->t_type("repayment")),
                    "loan_out"=>$helper->ledger_sum($row["m_id"], $helper->t_type("loan out")),
                    "loan_charges"=>$helper->ledger_sum($row["m_id"], $helper->t_type("loan charges")),
                    "membership"=>$helper->ledger_sum($row["m_id"], $helper->t_type("membership")),
                    "loan_form"=>$helper->ledger_sum($row["m_id"], $helper->t_type("loan_form")),
                
                ]);
                // $msg["total_saving"] += $helper->ledger_sum($row["m_id"], $helper->t_type("saving"));
            }
        }
        break;
    
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }

echo json_encode($msg);