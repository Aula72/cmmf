<?php

namespace Cmmf;
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// require_once "../config/db.php";

include_once "../config/constants.php";
include_once "../config/db.php";
class Helper{
    protected $conn;
    private $text = SECRET;
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
                $this->write_2_file("../error.txt", json_encode($err));
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
    public function get_user($id){
        $rt = $this->query("select *  from user where user_id=:id", [":id"=>$id]);
        $ty = $rt->fetch(\PDO::FETCH_ASSOC);
        return $ty;
    }
    public function get_token(){
        $this->checkToken();
        $tok = $_SERVER['HTTP_AUTH'];

        $rt = $this->query("select * from tokens where token=:token",[":token"=>$tok]);
        $y = $rt->fetch(\PDO::FETCH_ASSOC);
        if($y['user_id']==NULL){
            die(json_encode(["error"=>"Authentication error please login...", "logged"=>false]));
        }
        // $msg["logged"]  = true;
        return [
            "user_id"=>$y["user_id"],
            "logged"=>true,
            "user"=>$this->get_user($y["user_id"])         
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

    public function get_guaranters($id, $grp){
        $k = $this->query("select m_id,m_code, (select ifnull(sum(t_amount), 0)  from trans_action where trans_type_id=:ty and trans_action.m_id=group_member.m_id) as t_amount from group_member where g_id=:g and m_id !=:m;", [":m"=>$id, ":g"=>$grp,":ty"=>$this->t_type("saving")]);
        // $k = $this->query("select * from group_member where g_id=:group and m_id in (select m_id from account_balance where amount>:amount and m_id !=:id)",[":id"=>$id,":group"=>$grp, ':amount'=>$amount]);
        return $k->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function get_loan_amount($id){
        // $id = $this->get_loan_id($id);
        $loan = $this->query("select * from loans where lo_id=:lo or lo_code=:lo", [":lo"=>$id]);
        $loan = $loan->fetch(\PDO::FETCH_ASSOC);
        return $loan["lo_amount"];
    }

    public function guarant_balance($id){
        $ht = $this->query("select ifnull(sum(amount),0) as amount from guaranter where lo_id=:id",[":id"=>$id]);
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
        $sec = $this->text.time();
        $msg = "
        <html>
        <head>
        <title>OTP Verification</title>
        </head>
        <body>
            <p>Your are using CMMF your verification code is:</p>
            <h1 style='text-align: center; color: green;'>{$otp}</h1>
            <p>Please don't share your OTP with anyone</p>
            <p>Follow link to continues <a href='https://cmmf.fueless.co.ug/verifier/{$mail}' target='_blank'>Verify OTP</a></p>
            <p>Thanks for using CMMF</p>
        </body>
        </html>
        ";
        $headers = "From: ".OTP_MAIL."\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $subj  = "Verification OTP of CMMF";

        mail($mail, $subj, $msg, $headers);
    }

    public function get_relationship($id){
        $ht = $this->query("select * from kin_relations where r_id=:id", [":id"=>$id]);
        $ht = $ht->fetch(\PDO::FETCH_ASSOC);
        return $ht["r_name"];
    }

    public function get_financial_year($id){
        $ht = $this->query("select * from finanial_year where y_id=:id", [":id"=>$id]);
        $ht = $ht->fetch(\PDO::FETCH_ASSOC);
        return $ht["name"];
    }

    // public function number_with_zeros($num, $len){
    //     $i = 10**$len;
    //     $i = $i - $num;
    //     $i = strval($i);

    //     return $i.length;
    // }
    public function create_log($u_id, $u_statement){
        $this->query("insert into user_logs set user_id=:id, lo_statement=:statement", [":id"=>$u_id,":statement"=>$u_statement]);
    }

    public function loan_history($txt, $id, $typ){
        $this->query("insert into loan_history set lo_id=:id, txt=:amt, user_id=:user,lo_type=:typ",[
            ":id"=>$id,
            ":amt"=>$amount,
            ":typ"=>$typ,
            ":user"=>$this->get_token()['user_id']
        ]);
    }

    // public function user_types

    public function member_history($mid, $act, $txt){
        $this->query("insert into member_history set m_id=:mid, action=:act, text=:txt, user_id=:user",[
            ":mid"=>$mid,
            ":act"=>$act,
            ":user"=>$this->get_token()['user_id'],
            ":txt"=>$txt
        ]);
    }

    public function loan_balance($loid){
        $loan = $this->query("select * from loans where lo_id=:lo",[":lo"=>$loid]);
        $loan = $loan->fetch(\PDO::FETCH_ASSOC);

        if($loan["ls_id"]==1){
            $res = "T.B.D";
        }else if($loan["ls_id"]==3){
            $res = "CANCELLED";
        }else if($loan["ls_id"]==4){
            $res = "0";
        }else{
            $uo = $this->query("select ifnull(sum(amount),0) as amount from loan_payment where lo_id=:lo",[":lo"=>$loid]);
            $res = $uo->fetch(\PDO::FETCH_ASSOC);
            $res = intval((1+intval($loan["lo_rate"])/100)*intval($loan["lo_amount"]) - $res["amount"]);
        }
        return $res;
    }

    public function user_types($id){
        $user = $this->query("select * from user_types where uid=:u", [":u"=>$id]);
        $user = $user->fetch(\PDO::FETCH_ASSOC);

        return $user["uname"];
    }

    public function loanable_member($id, $t){
        $y = $this->query("select * from loanable_member where m_id=:i",[":i"=>$id]);
        if($y->rowCount()==0){
            $this->query("insert into loanable_member set m_id=:id, status=:s",[":id"=>$id, ":s"=>$t]);
        }else{
            $this->query("update loanable_member set status=:s where m_id=:id",[":id"=>$id, ":s"=>$t]);
        }
        
    }

    public function remove_record($tb, $id, $i){
        $this->query("delete from $tb where $id=:id",[":id"=>$i]);
    }

    public function check_amount($member, $week, $tran){
        $t0 = $this->query("select * from trans_action where m_id=:memb and w_id=:wk and trans_type_id=:t limit 1", [":memb"=>$member, ":wk"=>$week, ":t"=>$tran]);
        $t = $t0->fetch(\PDO::FETCH_ASSOC);

        return $t["t_amount"]==null?0:$t["t_amount"];
    }

    public function t_type($tran){
        $t0 = $this->query("select * from trans_types where ty_name=:t", [":t"=>$tran]);
        $t = $t0->fetch(\PDO::FETCH_ASSOC);

        return $t["ty_id"];
    }
    public function member_has_loan($id){
        $t = $this->query("select * from loanable_member where m_id=:id", [":id"=>$id]);
        $t = $t->fetch(\PDO::FETCH_ASSOC);
        return $t["status"]==0?false:true;
    }

    public function guaranter_percentage($id, $lo_id){
        $g = $this->query("select sum(amount) as amount from guaranter where m_id=:id and lo_id=:loan",[":id"=>$id, ":loan"=>$lo_id]);
        $g = $g->fetch(\PDO::FETCH_ASSOC);

        $r = $this->query("select sum(amount) as amount from guaranter where lo_id=:id", [":id"=>$lo_id]);
        $r = $r->fetch(\PDO::FETCH_ASSOC);

        try{
            $p = $g["amount"]/$r["amount"];
        }catch(DivisionByZeroError $e){
            $p = 0;
        }
        return round($p, 2);
    }
    
    public function deposit_to_ledger($ty = []){
        $mem = $ty["m_id"];
        $amount = $ty["amount"];
        $ledger = $ty["trans_type_id"];
        $week = $ty["w_id"];
        $t_desc = $ty["t_desc"];
        $t_code = $ty["t_code"];
        // die(json_encode($ty));

        $p = $this->query("select * from trans_action where w_id=:w and m_id=:m and trans_type_id=:id", [":m"=>$mem, ":w"=>$week, ":id"=>$ledger]);
        if(intval($amount)>0){
            if($p->rowCount()==0){
                $trans = $this->query("insert into trans_action set user_id=:user, w_id=:week, m_id=:member, trans_type_id=:trans, t_code=:code, t_amount=:amount, t_desc=:comment",[":user"=>$this->get_token()["user_id"],":member"=>$mem,":trans"=>$ledger,":code"=>$t_code,":amount"=>$amount,":comment"=>$t_desc, ":week"=>$week]);
            }else{
                $trans = $this->query("update trans_action set   t_amount=:amount, t_desc=:comment where w_id=:week and m_id=:member and trans_type_id=:trans",[":member"=>$mem,":trans"=>$ledger,":amount"=>$amount,":comment"=>$t_desc, ":week"=>$week]);
            }
            $this->create_log($this->get_token()["user_id"], "Transaction {$t_code} amnt {$amount} made");
            if($trans){
                $t = true;
            }
        }else{
            $t = false;
        }
        return $t;
    }

    public function ledger_sum($mem, $ledger){
        $p = $this->query("select ifnull(sum(t_amount), 0) as amount from trans_action where m_id=:mem and trans_type_id=:ty",[":mem"=>$mem, ":ty"=>$ledger]);
        $p = $p->fetch(\PDO::FETCH_ASSOC);
        return $p["amount"];
    }

    public function my_current_week($id){
        $mem = $this->get_member($id);
        $w = $this->query("select max(w_id) as w_id from weeks where g_id=:g", [":g"=>$mem["g_id"]]);
        $w = $w->fetch(\PDO::FETCH_ASSOC);
        return $w["w_id"];  
    }
    public function get_guarantee_balance($id){
        $p = $this->query("select ifnull(sum(amount), 0) as amount from guaranter_balance where m_id=:m",[":m"=>$id]);
        $p = $p->fetch(\PDO::FETCH_ASSOC);
        return $p["amount"];
    }
    public function my_guarant_balance($id, $lo){
        $p = $this->query("select ifnull(sum(amount), 0) as amount from guaranter_balance where m_id=:m and lo_id=:lo",[":m"=>$id, ":lo"=>$lo]);
        $p = $p->fetch(\PDO::FETCH_ASSOC);
        return $p["amount"];
    }
    public function my_loan($id){
        $p = $this->query("select * from loans where m_id=:m and ls_id='2'",[":m"=>$id]);
        $p = $p->fetch(\PDO::FETCH_ASSOC);
        return $p["lo_id"];
    }
    public function t_id(){
        return "TRS".time()."CMMF".rand(1000,9999);
    }
    public function pay_guaranter($lo, $g, $amount){
        
        $bal = $this->my_guarant_balance($g, $lo);
        
        $mem = $this->get_member($g);
        // die(json_encode(["g"=>$g, "bal"=>$bal, "mem"=>$mem]));
        $week1 = $this->query("select * from weeks where g_id=:g order by w_id desc limit 1",[":g"=>$mem["g_id"]]);
        $week1 = $week1->fetch(\PDO::FETCH_ASSOC);
        $week1 = $week1["w_id"];
        if(intval($bal)>0){
            $amount = $this->guaranter_percentage($g, $lo)*$amount;
            $this->deposit_to_ledger(["m_id"=>$g, "amount"=>$amount, "trans_type_id"=>$this->t_type("saving"), "t_code"=>$this->t_id()."N","t_desc"=>"Loan repayment", "w_id"=>$week1]);
            $gua = $this->query("select * from guaranter_balance where lo_id=:lo and m_id=:m limit 1",[":m"=>$g,':lo'=>$lo]);
            $gua = $gua->fetch(\PDO::FETCH_ASSOC);

            $bal = $gua["amount"] - $amount;
            $this->query("update guaranter_balance set amount=:amt where lo_id=:lo and m_id=:m",[":m"=>$g, ":lo"=>$lo, ":amt"=>$bal]);
            
        }
    }

    public function create_share($lo, $g){
        $loan = $this->get_loan_id($lo);
        $gua = $this->query("select ifnull(sum(amount), 0) as amount from guaranter where lo_id=:lo and m_id=:m", [":lo"=>$lo, ":m"=>$g]);
        try{
            $shr = $gua["amount"]/$loan["lo_amount"]*(1+$loan["lo_rate"]/100);
        }catch(Exception $e){
            $shr = 0;
        }

        $sher = $this->query("select * from guaranter_share where g_id=:g and lo_id=:lo",[":g"=>$g, ":lo"=>$lo]);
        if($sher->rowCount()>0){
            $this->query("update guaranter_share set share=:s where g_id=:g and lo_id=:lo",[":g"=>$g, ":lo"=>$lo, ":s"=>$shr]);
        }else{
            $this->query("insert into guaranter_share set share=:s where g_id=:g, lo_id=:lo",[":g"=>$g, ":lo"=>$lo, ":s"=>$shr]);
        }
    }

    public function write_2_file($file, $txt){
        $myfile = fopen($file, "a") or die("Unable to open file!");
        
        fwrite($myfile, date('d-m-Y H:i:s').">>>".$txt."\n");
        fclose($myfile);
    }

    public function new_worth($id){
        $loans = $this->query("select * from loans where m_id=:id and ls_id in (1, 4, 5)", [":id"=>$id]);
        $loans = $loans->fetch(\PDO::FETCH_ASSOC);
        
        $saving = $this->query("select ifnull(sum(t_amount),0) as t_amount from trans_action where m_id=:id and trans_type_id=:ti", [":id"=>$id, ":ti"=>$this->t_type("saving")]);
        $saving = $saving->fetch(\PDO::FETCH_ASSOC);

        $g_bal = $this->query("select ifnull(sum(amount),0) as amount from guaranter where m_id=:id  and lo_id in (select lo_id from loans where ls_id != 4)",[":id"=>$id]);
        $g_bal = $g_bal->fetch(\PDO::FETCH_ASSOC);

        $ty = $loans["lo_amount"]==null?0:$loans["lo_amount"];

        $p["m_id"] = $id;
        $p["saving"] = $saving["t_amount"];
        $p["loans"] = $ty;
        $p["g_bal"] = $g_bal["amount"];

        // $this->write_2_file('../error.txt', json_encode($p));
        $r =intval($saving["t_amount"]) - intval($loans["lo_amount"]*(1+$loans["lo_rate"])) - intval($g_bal["amount"]);
        return $r;
    }

}


