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

        }else{
            $msg["dash"] = [];
            
            // $weeks = $helper->query("select count(w_id) as ws from weeks");
            $w["num"] = "Click To Add";
            $w["img"] = "bi-plus";
            $w["name"] = "New Week";
            $w["url"] = "add-week";

            array_push($msg["dash"], $w);
            $weeks = $helper->query("select count(w_id) as ws from weeks");
            $w["num"] = $weeks->fetch(PDO::FETCH_ASSOC)['ws'];
            $w["img"] = "bi-calender";
            $w["name"] = "Weeks";
            $w["url"] = "weeks";

            array_push($msg["dash"], $w);

            $groups = $helper->query("select count(g_id) as ws from grouping");
            $w["num"] = $groups->fetch(PDO::FETCH_ASSOC)['ws'];
            $w["img"] = "bi-people";
            $w["name"] = "Groups";
            $w["url"] = "groups";

            array_push($msg["dash"], $w);
            
            // $groups = $helper->query("select count(m_id) as ws from group_member");
            // $w["num"] = $groups->fetch(PDO::FETCH_ASSOC)['ws'];
            // $w["img"] = "/assets/img/member.png";
            // $w["name"] = "Members";
            // $w["url"] = "#";

            // array_push($msg["dash"], $w);
            $groups = $helper->query("select count(ty_id) as ws from trans_types");
            $w["num"] = number_format($groups->fetch(PDO::FETCH_ASSOC)['ws']);
            $w["img"] = "bi-currency-exchange";
            $w["name"] = "Ledgers";
            $w["url"] = "ledgers";

            array_push($msg["dash"], $w);

            $groups = $helper->query("select count(lo_id) as ws from loans");
            $w["num"] = number_format($groups->fetch(PDO::FETCH_ASSOC)['ws']);
            $w["img"] = "bi-money";
            $w["name"] = "Loans";
            $w["url"] = "loans";

            array_push($msg["dash"], $w);
            $groups = $helper->query("select count(user_id) as ws from user");
            $w["num"] = number_format($groups->fetch(PDO::FETCH_ASSOC)['ws']);
            $w["img"] = "/assets/img/users.png";
            $w["name"] = "Admins";
            $w["url"]  = "admin";

            array_push($msg["dash"], $w);

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

$msg["logged"] = $helper->get_token()["logged"];
echo json_encode($msg);