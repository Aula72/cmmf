<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'user';
$data = json_decode(file_get_contents("php://input"), true);

switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            $id = $_GET["id"];
            $user = $helper->query("select * from $tb_name where user_id=:id",[":id"=>$id]);
            if($user->rowCount()>0){
                $msg["status"] = 1;
                $msg["admin"] = $user->fetch(PDO::FETCH_ASSOC);
            }else{
                $msg["message"] = "No such user";
                $msg["status"] = 0;
            }
        }else if(isset($_GET['status'])){
            $id = $_GET["status"];
            $user = $helper->query("select * from $tb_name where user_id=:id",[":id"=>$id]);
            if($user->rowCount()>0){
                $l = $user->fetch(PDO::FETCH_ASSOC);
                $lo = $l["status"]==1?0:1; 
                $msg["status"]=1;
                $msg["message"] = $lo==1?"User activated successfully...":"User deactivated successfully";
                $helper->query("update $tb_name set status=:ns where user_id=:us",[":ns"=>$lo, ':us'=>$id]);
            }
        }else{
            $user = $helper->query("select * from $tb_name");
            $msg["admin"] = [];
            foreach($user->fetchAll(PDO::FETCH_ASSOC) as $row){
                array_push($msg['admin'], $row);
            }
        }
        break;
    case 'POST':
        
        $data = json_decode(file_get_contents("php://input"), true);

        $lname = $data["lname"];
        $fname = $data["fname"];
        $mail = $data["mail"];
        $status = 2;
        $helper->required_fields([$mail, $fname, $lname, $status]);
        $check = $helper->query("select * from user where mail=:mail",[":mail"=>$mail]);
        // die(json_encode(["num"=>$check->rowCount()]));
        if($data["edit_user"]){
            // die(json_encode(["update"=>$data]));
            $status = $data["status"];
            $msg["status"] = 1;
            $mu = $helper->query("update  user set fname=:fname, lname=:lname, mail=:mail, status=:status where user_id=:id",[":fname"=>$fname, ":lname"=>$lname, ":mail"=>$mail, ':status'=>$status, ":id"=>$data["edit_user"]]);
            $msg["message"] = "Account updated successfully";
        }else{
            
            if($check->rowCount()>0){
                // die(json_encode(["num"=>$check->rowCount()]));
                $msg["status"] = 0;
                $msg["message"] = "Account already exists";
            }else{
                
                // die(json_encode(["create"=>$data]));
                $ty = $helper->query("insert into  user set 
                        fname=:fname, 
                        lname=:lname, 
                        mail=:mail, 
                        status=:status",
                    [
                        ":fname"=>$fname, 
                        ":lname"=>$lname, 
                        ":mail"=>$mail, 
                        ":status"=>$status
                    ]);
                // $helper->query("insert into $tb_name set fname=:f, lname=:ln, mail=:m, `status`=:s",[":f"=>$fname, ":ln"=>$lname, ":m"=>$mail, ":s"=>$status]);
                // die(json_encode(["simon"=>$ty]));
                
                $msg["status"] = 1;
                $msg["message"] = "Account created successfully";
               
                
            }
        }
    case 'PUT':
        $id = $_GET["id"];
        $mail = $data["mail"];
        $fname = $data["fname"];
        $lname = $data["lname"];
        $status = $data["status"];
        $helper->required_fields([$mail, $fname, $lname]);
        $helper->get_token();
        $user = $helper->query("update $tb_name set mail=:mail, fname=:fname, lname=:lname, status=:status where user_id=:id",[":mail"=>$mail, ":fname"=>$fname, ":lname"=>$lname, ":status"=>$status]);
        if($user){
            $msg["status"] = 1;
            $msg["message"] = "User was updated successfully...";
        }
        break;
    case 'DELETE':

        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }

echo json_encode($msg);