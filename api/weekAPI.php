<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"), true);
$tb_name = "weeks";
$helper->get_token();
switch($meth){
    case "GET":
        if(isset($_GET['id'])){
            $weeks = $helper->query("select * from weeks where w_id=:id or w_code=:id", [":id"=>$_GET['id']]);
            $msg["weeks"] = $weeks->fetch(PDO::FETCH_ASSOC)?$weeks->fetch(PDO::FETCH_ASSOC):"No such week";
        }else if(isset($_GET['grp'])){
            $weeks = $helper->query("select * from weeks where g_id=:g order by w_id desc", [':g'=>$_GET['grp']]);
            $msg["weeks"] = [];
            foreach($weeks->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg['weeks'], [
                    "w_id"=>$row["w_id"],
                    "w_code"=>$row["w_code"],
                    "g_id"=>$row["g_id"],
                    "w_date"=>$row['w_date'],
                    "g_code"=>$helper->group_code($row["g_id"]),
                    "y_id"=>$row["y_id"],
                    "year"=>$helper->get_financial_year($row["y_id"])
                ]);
            }
        }else{
            $weeks = $helper->query("select * from weeks order by w_id desc");
            $msg["weeks"] = [];
            foreach($weeks->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg['weeks'], [
                    "w_id"=>$row["w_id"],
                    "w_code"=>$row["w_code"],
                    "g_id"=>$row["g_id"],
                    "w_date"=>$row['w_date'],
                    "g_code"=>$helper->group_code($row["g_id"]),
                    "year"=>$helper->get_financial_year($row["y_id"])
                ]);
            }
        }
        
        break;
    case "POST":
        // $msg["weeks"] = $data;
        // die(json_encode($data));
        // $code = $helper->get_last_id("w_id","weeks") + 1;
        // $code = 'W'.$code;
        if(isset($_GET["year"])){
            $t = $_GET["year"];
            if(is_numeric($t)){
                $helper->query("insert into finanial_year set name=:na", [":na"=>$_GET["year"]]);
                $msg["message"] = "Financial Year Added Successfully";
                $msg["status"] =1;
                $helper->create_log($helper->get_token()["user_id"], "Added year, {$_GET["year"]}");
            }else{
                $msg["message"] = "Please enter number";
                $msg["status"] =0;
            }
            
        }else{
            $group = $data["group"];
            $code = $data['code'];
            $date = $data['dat'];
            $year  = $data["year"];
            $user = $helper->get_token()["user_id"];
            
            $helper->required_fields([$group, $code, $date, $year]);
            $weeks = $helper->query("insert into $tb_name set w_code=:code, g_id=:group, user_id=:user, w_date=:date, y_id=:year",[':code'=>$code, ":group"=>$group, "user"=>$user, ':date'=>$date, ":year"=>$year]);
            if($weeks){
                $msg["status"]=1;
                $msg["message"] = "Week $code was created successfully";
                $helper->create_log($helper->get_token()["user_id"], "Created week,  $code.");

            }else{
                $msg["status"]=0;
                $msg["message"] = "Operation failed";
            }
        }
        
        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
}

echo json_encode($msg);