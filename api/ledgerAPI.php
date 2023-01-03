<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'legder';
$data = json_decode(file_get_contents("php://input"), true);
$helper->get_token();
switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            if(isset($_GET['week'])){
                $h = $helper->query("select * from trans_action where trans_type_id=:id and w_id=:w",[':id'=>$_GET['id'], ':w'=>$_GET['week']]);
                $msg["trans"] = [];
                $p = 0;
                foreach($h->fetchAll(\PDO::FETCH_ASSOC) as $row){
                    $m = $helper->get_member($row["m_id"]);
                    array_push($msg["trans"], Array(
                        "created_at"=>$row["created_at"],
                        "m_id"=>$row["m_id"],
                        "t_amount"=>$row["t_amount"],
                        "t_code"=>$row["t_code"],
                        "t_desc"=>$row["t_desc"],
                        "t_id"=>$row["t_id"],
                        "trans_type_id"=>$row["trans_type_id"],
                        "updated_at"=>$row["updated_at"],
                        "user_id"=>$row["user_id"],
                        "w_id"=>$row["w_id"],
                        "member"=>$m["m_code"],
                        "week"=>$helper->get_week($row["w_id"])["w_code"]
                    ));

                    $p += $row["t_amount"];
                    
                }
                $msg["trans_sum"] = number_format($p);
            }else{
                $h = $helper->query("select * from trans_action where t_id=:id",[':id'=>$_GET['id']]);
                $row = $h->fetch(\PDO::FETCH_ASSOC);
                
                $msg["created_at"]=$row["created_at"];
                $msg["m_id"]=$row["m_id"];
                $msg["t_amount"]=$row["t_amount"];
                $msg["t_code"]=$row["t_code"];
                $msg["t_desc"]=$row["t_desc"];
                $msg["t_id"]=$row["t_id"];
                $msg["trans_type_id"]=$row["trans_type_id"];
                $msg["updated_at"]=$row["updated_at"];
                $msg["user_id"]=$row["user_id"];
                $msg["w_id"]=$row["w_id"];
                $msg["member"]=$helper->get_member($row['m_id'])['m_code'];
                $msg["week"]=$helper->get_week($row["w_id"])["w_code"];

            }
        }else{

        }
        break;
    case 'POST':
        $user_id = $helper->get_token()['user_id'];
        $m_id = $data["member"];	
        $amount = $data['amount'];	
        $l_type = $data["ltype"];
        $trans = uniqid();
        // die(json_encode($data));
        $helper->required_fields([$m_id, $amount, $l_type]);
        $r = $helper->query("insert into $tb_name set user_id=:user, m_id=:m, amount=:a, l_type=:l, trans_id=:t",[":user"=>$user_id,":m"=>$m_id, ":a"=>$amount,":l"=>$l_type, ':t'=>$trans]);
        if($r){
            $msg["status"] = 1;
            $msg["message"] = "Transaction $trans was made successfully!";
        }
        break;
    case 'PUT':

        break;
    case 'DELETE':
        $helper->remove_record("trans_types", "ty_id", $_GET['id']);
        $msg["message"] = "Record removed successfully";
        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }

echo json_encode($msg);