<?php

namespace Cmmf;
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// require_once "../config/db.php";

include_once "../config/constants.php";
include_once "../config/db.php";
class Helper{
    protected $conn;
    public function __construct(){
        $this->conn = new \Cmmf\Database();
        $this->conn = $this->conn->connect();
    }
    public function query($stmt, $params = []){
            try{
                $t = $this->conn->prepare($stmt);
                $t->execute($params);
            }catch(\PDOException $e){
                $err["message"] = $e->getMessage();
                $err["file"] = $e->getFile();
                $err["line"] = $e->getLine();
                $err["code"] = $e->getCode();
                
                die(json_encode(['error'=>$err]));
            }
            
            return $t;
    }

    public function req_method($u){
        if($_SERVER['REQUEST_METHOD'] !== strtoupper($u)){
            die(json_encode(["error"=>"Invalid method, expected {$u}"]));
        }
    }

    public function get_last_id($id, $table){
        $rt = $this->query("select max($id) as maxp from $table");
        $ty = $rt->fetch(\PDO::FETCH_ASSOC);
        return $ty['maxp'];
    }
    public function get_token(){
        $this->checkToken();
        $tok = $_SERVER['HTTP_AUTH'];

        $rt = $this->query("select * from tokens where token=:token",[":token"=>$tok]);
        $y = $rt->fetch();
        if($y['user_id']==NULL){
            die(json_encode(["error"=>"Authentication error please login..."]));
        }
        return [
            "user_id"=>$y["user_id"]
        ];
    }
    public function checkToken(){
        if(!isset($_SERVER['HTTP_AUTH'])){
             die(json_encode(['error'=>'Authentication error...']));
        }
    }
    public function create_token($id){
        // $token = sha1(date('Y-m-d').$id.rand(1000,9000000));
        // $_SESSION['TOKEN']=$token;
        $rt = $this->query("delete from tokens where user_id=:id",[':id'=>$id]);
        $token = [
            "id"=>$id,
            "date"=>date('m-d-Y h:i:s a', time()),
            "rand"=>rand(10000, 9999999),
            "ip"=>$_SERVER['REMOTE_ADDR'],
        ];
        $token = sha1(json_encode($token));
        $this->query("insert into tokens set token=:token, user_id=:user_id",[':token'=>$token, ':user_id'=>$id]);
        return $token;
    }
    public function group_code($id){
        $h = $this->query("select * from grouping where g_id=:id",[':id'=>$id]);
        $grp = $h->fetch(\PDO::FETCH_ASSOC);
        // die(json_encode(["error"=>$grp["g_code"]]));
        return $grp["g_code"];
    }
    public function required_fields($r = []){
        foreach($r as $t){            
            if($t==='' || $t===null){
                die(json_encode(["error"=>"Some fields are not filled check form and try again..."]));
            }
            
        }
    }
    public function ledge_multiplier($id, $amount = 0){
        $h = $this->query("select * from trans_types where ty_id=:id",[':id'=>$id]);
        $grp = $h->fetch(\PDO::FETCH_ASSOC);
        // die(json_encode(["error"=>$grp["g_code"]]));
        return number_format(intval($grp["mult"])*$amount);
    }

    public function get_member($id){
        $ty = $this->query("select * from group_member where m_id=:id",[":id"=>$id]);
        return $ty->fetch(\PDO::FETCH_ASSOC);
    }
    public function get_week($id){
        $ty = $this->query("select * from weeks where w_id=:id",[":id"=>$id]);
        return $ty->fetch(\PDO::FETCH_ASSOC);
    }
    public function create_account($m_id, $user_id){
        return $this->query("insert into account_balance set m_id=:m, user_id=:id, amount=:a",[":m"=>$m_id, ":id"=>$user_id, ":a"=>0]);
    }
    public function account_balance($id){
        $bal = $this->query("select * from account_balance where m_id=:id",[":id"=>$id]);
        $h = $bal->fetch(\PDO::FETCH_ASSOC);

        return $h["amount"];
    }

    public function update_account($acc, $amt, $mlt){
        $amt1 = $this->account_balance($acc);
        $amt2 = $amt1 + $this->get_ledger_multiplier($mlt)*$amt;
        $this->query("update account_balance set amount=:a, updated_on=:on where m_id=:m",[":a"=>$amt2, ":m"=>$acc, ":on"=>date('Y-m-d H:i:s')]);
    }

    public function get_ledger_multiplier($id){
        $h = $this->query("select * from trans_types where ty_id=:id",[':id'=>$id]);
        $grp = $h->fetch(\PDO::FETCH_ASSOC);
        // die(json_encode(["error"=>$grp["g_code"]]));
        return intval($grp["mult"]);
    }
    public function sum_bal_type($id, $t){
        $k = $this->query("select sum(t_amount) as amnt from trans_action where m_id=:id and trans_type_id=:type", [":id"=>$id, ":type"=>$t]);
       
        $k = $k->fetch(\PDO::FETCH_ASSOC);
        return $k["amnt"];
    }
    
    public function get_loan_id($id){
        $bal = $this->query("select * from loans where lo_code=:id or lo_id=:id",[":id"=>$id]);
        $h = $bal->fetch(\PDO::FETCH_ASSOC);

        return $h;
    }

    public function get_guaranters($id, $grp,$amount){
        $k = $this->query("select * from group_member where g_id=:group and m_id in (select m_id from account_balance where amount>:amount and m_id !=:id)",[":id"=>$id,":group"=>$grp, ':amount'=>$amount]);
        return $k->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get_loan_amount($id){
        // $id = $this->get_loan_id($id);
        $loan = $this->query("select * from loans where lo_id=:lo or lo_code=:lo", [":lo"=>$id]);
        $loan = $loan->fetch(\PDO::FETCH_ASSOC);
        return $loan["lo_amount"];
    }

    public function guarant_balance($id){
        $ht = $this->query("select sum(amount) as amount from guaranter where lo_id=:id",[":id"=>$id]);
        $ht = $ht->fetch(\PDO::FETCH_ASSOC);
        return $ht["amount"];
    }

    public function loan_status($id){
        $ht = $this->query("select * from loan_status where ls_id=:id", [":id"=>$id]);
        $ht = $ht->fetch(\PDO::FETCH_ASSOC);
        return $ht["name"];
    }

    public function get_ledger_name($id){
        $ht = $this->query("select * from trans_types where ty_id=:id", [":id"=>$id]);
        $ht = $ht->fetch(\PDO::FETCH_ASSOC);
        return $ht["ty_name"];
    }

    public function mail_send($otp, $mail){
        // ini_set("SMTP", $mail);
        // ini_set("sendmail_from", OTP_MAIL);

        // // $message = "The mail message was sent with the following mail setting:\r\nSMTP = aspmx.l.google.com\r\nsmtp_port = 25\r\nsendmail_from = YourMail@address.com";

        // $headers = "From: ".OTP_MAIL;

        // mail("Sending@provider.com", $grt, $msg, $headers);

        $msg = "<h2>CMMF OTP</h2><p>Enter {$otp} as your otp to continue...</p><p>Please don't share your OTP with anyone!</p>";
        $headers = "From: ".OTP_MAIL;
        $subj  = "Verification OTP of CMMF";

        mail($mail, $subj, $msg, $headers);
    }
}

// $h = new Helper;
// echo json_encode(["err"=>$h->sum_bal_type(4, 4)]);