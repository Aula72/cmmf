<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"), true);
$tb_name = "weeks";
switch($meth){
    case "GET":
        if(isset($_GET['id'])){
            $weeks = $helper->query("select * from weeks where w_id=:id or w_code=:id", [":id"=>$_GET['id']]);
            $msg["weeks"] = $weeks->fetch(PDO::FETCH_ASSOC)?$weeks->fetch(PDO::FETCH_ASSOC):"No such week";
        }else{
            $weeks = $helper->query("select * from weeks");
            $msg["weeks"] = [];
            foreach($weeks->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg['weeks'], [
                    "w_id"=>$row["w_id"],
                    "w_code"=>$row["w_code"],
                    "g_id"=>$row["g_id"],
                    "w_date"=>$row['w_date'],
                    "g_code"=>$helper->group_code($row["g_id"])
                ]);
            }
        }
        
        break;
    case "POST":
        // $msg["weeks"] = $data;
        // die(json_encode($data));
        // $code = $helper->get_last_id("w_id","weeks") + 1;
        // $code = 'W'.$code;
        $group = $data["group"];
        $code = $data['code'];
        $date = $data['dat'];
        $user = $helper->get_token()["user_id"];
        
        $helper->required_fields([$group, $code, $date]);
        $weeks = $helper->query("insert into $tb_name set w_code=:code, g_id=:group, user_id=:user, w_date=:date",[':code'=>$code, ":group"=>$group, "user"=>$user, ':date'=>$date]);
        if($weeks){
            $msg["status"]=1;
            $msg["message"] = "Week $code was created successfully";
        }else{
            $msg["status"]=0;
            $msg["message"] = "Operation failed";
        }
        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
}

echo json_encode($msg);