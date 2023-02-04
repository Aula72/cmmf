<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];

$data = json_decode(file_get_contents("php://input"), true);
$helper->get_token();
switch($meth){
    case 'GET':
        //groups
        $q = $_GET["q"];
        $grp = $helper->query("select * from grouping where g_code like :src or g_name like :src or g_location like :src",[":src"=>'%'.$q.'%']);
        $msg["groups"] = [];
        foreach($grp->fetchAll(\PDO::FETCH_ASSOC) as $row){
            array_push($msg["groups"], $row);
        }
        $grp = $helper->query("select * from group_member where m_code like :src or m_fname like :src or m_lname like :src or m_nin like :src or m_phone like :src",[":src"=>'%'.$q.'%']);
        $msg["members"] = [];
        foreach($grp->fetchAll(\PDO::FETCH_ASSOC) as $row){
            array_push($msg["members"], $row);
        }
        $grp = $helper->query("select * from weeks where w_code like :src or w_date like :src",[":src"=>'%'.$q.'%']);
        $msg["weeks"] = [];
        foreach($grp->fetchAll(\PDO::FETCH_ASSOC) as $row){
            array_push($msg["weeks"], $row);
        }
        $grp = $helper->query("select * from loans where lo_code like :src",[":src"=>'%'.$q.'%']);
        $msg["loans"] = [];
        foreach($grp->fetchAll(\PDO::FETCH_ASSOC) as $row){
            array_push($msg["loans"], $row);
        }
        $grp = $helper->query("select * from user where mail like :src or fname like :src or lname like :src",[":src"=>'%'.$q.'%']);
        $msg["users"] = [];
        foreach($grp->fetchAll(\PDO::FETCH_ASSOC) as $row){
            array_push($msg["users"], $row);
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