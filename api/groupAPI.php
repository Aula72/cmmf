<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"), true);
$tb_name = 'grouping';
$helper->get_token();
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
            
        }else if(isset($_GET['payment'])){
            $u = $helper->query("SELECT distinct group_member.m_id, group_member.m_code, group_member.g_id, ifnull((select DISTINCT ifnull(trans_action.t_amount, 0) from trans_action where    trans_action.w_id=:w and trans_action.trans_type_id=:t and trans_action.m_id = group_member.m_id limit 1), 0) as t_amount  FROM group_member, trans_action where g_id=:g and trans_action.m_id = group_member.m_id;", [":g"=>$_GET["grp"], ":w"=>$_GET["week"], ":t"=>$_GET["ledger"]]);
            $msg["payments"] = [];
            foreach($u->fetchAll(\PDO::FETCH_ASSOC) as $row){
                array_push($msg["payments"], $row);
            }
        }else if(isset($_GET['mem'])){
            // die(json_encode($_GET));
            $u = $helper->query("select ifnull(t_amount, 0) as t_amount from trans_action where m_id = :g  and w_id=:w and trans_type_id=:t limit 1", [":g"=>$_GET["mem"], ":w"=>$_GET["week"], ":t"=>$_GET["ledger"]]);
            $u = $u->fetch(\PDO::FETCH_ASSOC);
            $msg["payments"] = $u["t_amount"];
        }else{
            $group = $helper->query("select * from $tb_name");
            $msg["group"] = [];
            foreach($group->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg['group'], $row);
            }
        }
        break;
    case 'POST':
        if($_GET["id"]){
            $name = $data["name"];
            $helper->get_token();
            $loc = $data["location"];
            $code = $_GET['id'];
            $group = $helper->query("update $tb_name set  g_name=:name, g_location=:location where g_code=:code",[":code"=>$code,":name"=>$name,":location"=>$loc]);
            if($group){
                $msg["status"]=1;
                $msg["message"] = "Group was update successfully";
                $helper->create_log($helper->get_token()["user_id"], "Group {$code} updated");
            }else{
                $msg["status"]=0;
                $msg["message"] = "Operation failed";
            }
        }else{
            $code = $helper->get_last_id('g_id', $tb_name)+1;
            $code = $data["code"];
            $name = $data["name"];
            $user = $helper->get_token()["user_id"];
            $loc = $data["location"];

            $group = $helper->query("insert into $tb_name set g_code=:code, g_name=:name, g_location=:location, user_id=:user",[":code"=>$code,":name"=>$name,":location"=>$loc,':user'=>$user]);
            if($group){
                $msg["status"]=1;
                $msg["message"] = "Group $code was created successfully";
                $helper->create_log($helper->get_token()["user_id"], "Group {$code} added");
            }else{
                $msg["status"]=0;
                $msg["message"] = "Operation failed";
            }
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