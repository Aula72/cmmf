<?php
namespace Cmmf;
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
                die(json_encode(["error"=>"All fields are required..."]));
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
}