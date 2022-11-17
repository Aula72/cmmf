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
            $ty = $helper->query("select * from next_of_kin where n_id=:id", [":id"=>$_GET["id"]]);
            $ty = $ty->fetch(\PDO::FETCH_ASSOC);
            $msg["kin"] = [
                "n_id"=>$ty["n_id"],
                "m_id"=>$ty["m_id"],
                "full_name"=>$ty["n_fname"]." ".$ty["n_lname"],
                "phone"=>$ty["n_phone"],
                "relation"=>$helper->get_relationship($ty["n_relation"]),
                "location"=>$ty["n_location"],
                "dob"=>$ty["n_dob"]
            ];
        }else if(isset($_GET['member'])){
            $ty = $helper->query("select * from next_of_kin where m_id=:id", [":id"=>$_GET["member"]]);
            $msg["next_of_kin"] = [];
            foreach($ty->fetchAll(\PDO::FETCH_ASSOC) as $row){
                array_push($msg["next_of_kin"], $row);
            }
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
        // die(json_encode($data));
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