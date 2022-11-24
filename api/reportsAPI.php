<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = '';
$data = json_decode(file_get_contents("php://input"), true);
$helper->get_token();
switch($meth){
    case 'GET':
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

            $msg["reports"]  = [];
            foreach($y->fetchAll(\PDO::FETCH_ASSOC) as $row){
                array_push($msg["reports"], $row);
            }
        }else{
            
        }
        break;
    case 'POST':

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