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
                $msg["status"] = 1;
            }else{
                $msg["loan"] = "No such loan";
                $msg["status"] = 0;
            }
        }else{
            $loans = $helper->query("select * from loans order by lo_id desc");
            $msg["loans"] = [];
            foreach($loans->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg["loans"], $row);
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

        $helper->required_fields([$lo_code, $lo_amount, $lo_expiry,$lo_rate]);
        $loan  =  $helper->query("insert into $tb_name set 	lo_code=:code,	lo_rate=:rate, lo_expiry=:expiry,m_id=:member,user_id=:user,lo_amount=:amount",[":code"=>$lo_code,":rate"=>$lo_rate,":member"=>$m_id,":amount"=>$lo_amount, ":user"=>$user_id]);
        if($loan){
            $msg["status"]=1;
            $msg["message"] = "Loan $lo_code was created successfully...";
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