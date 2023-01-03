<?php 
header("Content-Type: application/json");
include_once "../controllers/Helpers.php";
$helper = new \Cmmf\Helper;
$meth = $_SERVER['REQUEST_METHOD'];
$tb_name = 'group_member';
$data = json_decode(file_get_contents("php://input"), true);
$helper->get_token();
switch($meth){
    case 'GET':
        if(isset($_GET['id'])){
            $id = intval($_GET['id']);
            $member = $helper->query("select * from $tb_name where m_id=:id or m_code=:id", [":id"=>$id]);
            // $msg["member"] = $member->fetch(\PDO::FETCH_ASSOC)?$group->fetch(\PDO::FETCH_ASSOC):"No such member...";
            if($member->rowCount()>0){
                $msg["member"] = $member->fetch(\PDO::FETCH_ASSOC);
                //next of kin
                $nxt = $helper->query("select * from next_of_kin where m_id=:id",[':id'=>$id]);
                $msg["next_of_kin"] = [];
                foreach($nxt->fetchAll(\PDO::FETCH_ASSOC) as $row){
                    array_push($msg['next_of_kin'], Array(
                        "n_id"=>$row["n_id"],
                        "m_id"=>$row["m_id"],
                        "full_name"=>$row["n_fname"]." ".$row["n_lname"],
                        "phone"=>$row["n_phone"],
                        "relation"=>$helper->get_relationship($row["n_relation"])
                    ));
                }
                //loans 
                $loan = $helper->query("select * from loans where m_id=:id", [':id'=>$id]);
                $msg["loans"] = [];
                foreach($loan->fetchAll(\PDO::FETCH_ASSOC) as $row){
                    array_push($msg['loans'], $row);
                }
                $ledger = $helper->query("select * from legder where m_id=:id", [':id'=>$id]);
                $msg["ledgers"] = [];
                foreach($ledger->fetchAll(\PDO::FETCH_ASSOC) as $row){
                    array_push($msg['ledgers'], [
                        "l_id"=>$row["l_id"],
                        "user_id"=>$row["user_id"],
                        "m_id"=>$row["m_id"],
                        "amount"=>$row["amount"],
                        "amount_ledger"=>$helper->ledge_multiplier($row["l_type"], $row["amount"]),
                        "trans_id"=>$row["trans_id"],
                        "created_at"=>$row["created_at"],
                        "l_type"=>$row["l_type"],
                    ]);
                }
                $msg["group_code"] = $helper->group_code($msg["member"]["g_id"]);
                $msg["balance"] = $helper->account_balance($id)==null?0:$helper->account_balance($id);
                $msg["savings"] = $helper->sum_bal_type($id, 4)==null?0:$helper->sum_bal_type($id, 4);
                $msg["social_fund"] = $helper->sum_bal_type($id, 5)==null?0:$helper->sum_bal_type($id, 5);
                $msg["fines"] = $helper->sum_bal_type($id, 6)==null?0:$helper->sum_bal_type($id, 6);
                $msg["education_in"] = $helper->sum_bal_type($id, 7)==null?0:$helper->sum_bal_type($id, 7);
                $msg["education_out"] = $helper->sum_bal_type($id, 8)==null?0:$helper->sum_bal_type($id, 8);
                $msg["subscription"] = $helper->sum_bal_type($id, 9)==null?0:$helper->sum_bal_type($id, 9);
                $msg["repayment"] = $helper->sum_bal_type($id, 10)==null?0:$helper->sum_bal_type($id, 10);
                $msg["loan_out"] = $helper->sum_bal_type($id, 11)==null?0:$helper->sum_bal_type($id, 11);
                $msg["loan_charge"] = $helper->sum_bal_type($id, 12)==null?0:$helper->sum_bal_type($id, 12);
                $msg["loan_forms"] = $helper->sum_bal_type($id, 13)==null?0:$helper->sum_bal_type($id, 13);
                $msg["membership"] = $helper->sum_bal_type($id, 14)==null?0:$helper->sum_bal_type($id, 14);
                $msg["ids"] = $id;
                $transaction = $helper->query("select * from trans_action where m_id=:me", [":me"=>$id]);
                $msg["transaction"] = [];
                foreach($transaction->fetchAll(PDO::FETCH_ASSOC) as $row){
                    array_push($msg["transaction"], $row);
                }

                $history = $helper->query("select * from member_history order by id desc");
                $msg["history"] = [];
                foreach($history->fetchAll(PDO::FETCH_ASSOC) as $row){
                    array_push($msg["history"], [
                        "id"=>$row["id"],
                        "user_id"=>$row["user_id"],
                        "created_at"=>$row["created_at"],
                        "action"=>$row["action"],
                        "text"=>json_decode($row["text"])
                    ]);
                }
            }else{
                $msg["member"] = "No such member";
            }
        }else if(isset($_GET['group'])){
            $id = $_GET['group'];
            $me = $helper->query("select * from $tb_name where g_id=:id",[':id'=>$id]);

            $t  = [];
            foreach($me->fetchAll(\PDO::FETCH_ASSOC) as $row){
                array_push($t, $row);
            }
            $msg["members"] = $t;
        }else{
            $me = $helper->query("select * from $tb_name");
            $t  = [];
            foreach($me->fetchAll(\PDO::FETCH_ASSOC) as $row){
                array_push($t, $row);
            }
            $msg["members"] = $t;
        }
        
        break;
    case 'POST':

        $helper->get_token();
        if($data["edit_me"]){
            $id= $data['edit_me'];
            // $code = $helper->get_last_id($tb_name, 'm_id')+1;
            $m_code = $data['mcode']; 	
            // $g_id = $data["code"]; 	
            $id= $data['edit_me'];
            $user_id=$helper->get_token()['user_id']; 
            $m_fname = $data["fname"]; 	
            $m_lname = $data["lname"];
            $m_phone = $data["phone"];
            $m_nin = $data["nin"];
            $m_gender=$data["gender"];
            $m_dob = $data["dob"];
            $g_id = $data["grp"];

            $h = $helper->query("update $tb_name set  m_code=:code, g_id=:grp,  m_fname=:fname, m_lname=:lname,m_phone=:phone, m_nin=:nin,m_gender=:gender,	m_dob=:dob where m_id=:id",[
                ":code"=>$m_code,
                ":grp"=>$g_id,
                ":id"=>$id,
                ":fname"=>$m_fname,
                ":lname"=>$m_lname,
                ":phone"=>$m_phone,
                ":nin"=>$m_nin,
                ":dob"=>$m_dob,
                ":gender"=>$m_gender
            ]);
            if($h){
                $msg["message"] = "Member update successfully";
                $msg["status"] = 1;
                $rec["code"]=$m_code;
                $rec["grp"]=$g_id;
                $rec["id"]=$id;
                $rec["fname"]=$m_fname;
                $rec["lname"]=$m_lname;
                $rec["phone"]=$m_phone;
                $rec["nin"]=$m_nin;
                $rec["dob"]=$m_dob;
             
                $helper->member_history($id,'EDT', json_encode($rec));
            }else{
                $msg["message"] = "Updateing member failed";
                $msg["status"] = 0;
            }
        }else{
            $m_code = $data["mcode"];	
            $g_id = $data["grp"]; 	
            $user_id=$helper->get_token()['user_id']; 
            $m_fname = $data["fname"]; 	
            $m_lname = $data["lname"];
            $m_phone = $data["phone"];
            $m_nin = $data["nin"];
            $m_gender=$data["gender"];
            $m_dob = $data["dob"];
            // die(json_encode($data));
            $helper->required_fields([$m_nin, $m_code, $g_id, $m_fname,$m_lname, $m_phone, $m_dob, $m_gender]);
            $h = $helper->query("insert into $tb_name set m_code=:code, g_id=:grp, user_id=:user, m_fname=:fname, m_lname=:lname,m_phone=:phone, m_nin=:nin,m_gender=:gender,	m_dob=:dob",[
                ":code"=>$m_code,
                ":grp"=>$g_id,
                ":user"=>$user_id,
                ":fname"=>$m_fname,
                ":lname"=>$m_lname,
                ":phone"=>$m_phone,
                ":nin"=>$m_nin,
                ":dob"=>$m_dob,
                ":gender"=>$m_gender
            ]);
            if($h){
                $id = $helper->get_last_id("m_id", "group_member");
                // die(json_encode(["id"=>$id]));
                $msg["message"] = "Member added successfully";
                $msg["status"] = 1;
                $helper->create_account($id, $helper->get_token()["user_id"]);
                $helper->member_history(
                    $id,'CRT', 
                    json_encode([
                        "code"=>$m_code,
                        "grp"=>$g_id,
                        "id"=>$helper->get_last_id('m_id', 'group_member'),
                        "fname"=>$m_fname,
                        "lname"=>$m_lname,
                        "phone"=>$m_phone,
                        "nin"=>$m_nin,
                        "dob"=>$m_dob
                    ]));

                $helper->loanable_member($id, 1);
            }else{
                $msg["message"] = "Adding member failed";
                $msg["status"] = 0;
            }
        }
        
        break;
    case 'PUT':
        // $helper->get_last_id();
        $id= $_GET['id'];
        $code = $helper->get_last_id($tb_name, 'm_id')+1;
        $m_code = $_GET['id']; 	
        // $g_id = $data["code"]; 	
        $id= $_GET['id'];
        $user_id=$helper->get_token()['user_id']; 
        $m_fname = $data["fname"]; 	
        $m_lname = $data["lname"];
        $m_phone = $data["phone"];
        $m_nin = $data["nin"];
        $m_gender=$data["gender"];
        $m_dob = $data["dob"];

        $h = $helper->query("update $tb_name set m_code=:code, g_id=:grp,  m_fname=:fname, m_lname=:lname,m_phone=:phone, m_nin=:nin,m_gender=:gender,	m_dob=:dob where m_id=:id or m_code=:id",[
            ":code"=>$m_code,
            ":grp"=>$g_id,
            ":id"=>$id,
            ":fname"=>$m_fname,
            ":lname"=>$m_lname,
            ":phone"=>$m_phone,
            ":nin"=>$m_nin,
            ":dob"=>$m_dob
        ]);
        if($h){
            $msg["message"] = "Member update successfully";
            $msg["status"] = 1;
            $helper->member_history($id,'EDT', json_encode(["code"=>$m_code,
            "grp"=>$g_id,
            "id"=>$id,
            "fname"=>$m_fname,
            "lname"=>$m_lname,
            "phone"=>$m_phone,
            "nin"=>$m_nin,
            "dob"=>$m_dob]));
        }else{
            $msg["message"] = "Updateing member failed";
            $msg["status"] = 0;
        }
        break;
    case 'DELETE':
        $helper->remove_record("group_member", "m_id", $_GET['id']);
        $msg["message"] = "Record removed successfully";
        break;
    default:
        die(json_encode(["error"=>"Invalid operation"]));
        break;
    }
$msg["logged"] = $helper->get_token()["logged"];
echo json_encode($msg);