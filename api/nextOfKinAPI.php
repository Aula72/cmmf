<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'next_of_kin';
$data = json_decode(file_get_contents("php://input"), true);

switch($meth){
    case 'GET':
        if(isset($_GET['id'])){

        }else{

        }
        break;
    case 'POST':
        $m_id = $data["member"];
        $n_fname = $data["fname"];
        $n_lname = $data["lname"];
        $n_relation = $data["relation"];	
        $n_phone = $data["phone"];
        $n_location = $data["location"];
        $n_dob = $data["dob"];
        $helper->required_fields([$m_id, $n_fname, $n_lname,$n_relation, $n_phone,$n_dob, $n_location,]);
        $ht = $helper->query("insert into $tb_name set m_id=:member,n_fname=:fname,n_lname=:lname,n_relation=:relation,	n_phone=:phone,	n_location=:location,	n_dob=:dob",["member"=>$m_id, ":fname"=>$n_fname, ":lname"=>$n_lname, ":relation"=>$n_relation, ":phone"=>$n_phone, ":location"=>$n_location, ":dob"=>$n_dob]);
        if($ht){
            $msg["status"] = 1;
            $msg["message"] = "Next of Kin added successfully to user details...";
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