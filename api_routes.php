<?php 
$r->set_route(['/api/users', "api/user.php",'GET']);


foreach($r->get_routes() as $route){ 
    
    if(strcmp("/v2{$route[0]}",$url)==0){
        $ty = explode('/',"/v2{$route[0]}");
        $_GET['id'] = isset($ty[3])?$ty[3]:null;
        if( $route[2]==$meth){
            require_once $route[1];
            die();
        }else{
            die(json_encode(["error"=>"Method not allowed, expected method is {$route[2]}"]));
        }
        
    }
}
echo json_encode(['message'=>'Operation not allowed please, chech your endpoint and method....']);
