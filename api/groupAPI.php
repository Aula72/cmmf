<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"), true);
$tb_name = 'grouping';
switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            $group = $helper->query("select * from $tb_name where g_id=:id or g_code=:id", [":id"=>$_GET['id']]);
            if($group->rowCount()>0){
                $msg["id"] = $_GET['id'];                
                $members = $helper->query("select * from group_member where g_id=:id",[":id"=>$_GET['id']]);
                
                $msg["members"] = [];
                foreach($members->fetchAll(\PDO::FETCH_ASSOC) as $row){
                    array_push($msg['members'], $row);
                }
                $msg["num_members"] = $members->rowCount();
                $msg["group"] = $group->fetch(\PDO::FETCH_ASSOC);
            }else{
                $msg["group"] = "No such group";
                $msg["status"] = 0;
            }
            
        }else{
            $group = $helper->query("select * from $tb_name");
            $msg["group"] = [];
            foreach($group->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg['group'], $row);
            }
        }
        break;
    case 'POST':
        $code = $helper->get_last_id('g_id', $tb_name)+1;
        $code = 'G'.$code;
        $name = $data["name"];
        $user = $helper->get_token()["user_id"];
        $loc = $data["location"];

        $group = $helper->query("insert into $tb_name set g_code=:code, g_name=:name, g_location=:location, user_id=:user",[":code"=>$code,":name"=>$name,":location"=>$loc,':user'=>$user]);
        if($group){
            $msg["status"]=1;
            $msg["message"] = "Group $code was created successfully";
        }else{
            $msg["status"]=0;
            $msg["message"] = "Operation failed";
        }
        break;
    case 'PUT':
        $name = $data["name"];
        $helper->get_token();
        $loc = $data["location"];
        $code = $_GET['id'];
        $group = $helper->query("update $tb_name set  g_name=:name, g_location=:location where g_code=:code",[":code"=>$code,":name"=>$name,":location"=>$loc]);
        if($group){
            $msg["status"]=1;
            $msg["message"] = "Group was update successfully";
        }else{
            $msg["status"]=0;
            $msg["message"] = "Operation failed";
        }
        break;
    case 'DELETE':

        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }

echo json_encode($msg);