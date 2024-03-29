<?php 
// echo json_encode($_SERVER);
$query_string = $_SERVER["QUERY_STRING"];
$po = explode('/', $_SERVER['REQUEST_URI']);
$r->set_route(['/', "views/index.php",'GET']);
$r->set_route(['/home', 'views/homes.php', 'GET']);
$r->set_route(["/otp", "views/pin.php", "GET"]);
$r->set_route(['/login', 'views/login.php', 'GET']);
$r->set_route(['/add-member', 'views/add-member.php','GET']);
$r->set_route(['/members', 'views/members.php','GET']);
$r->set_route(["/members/{$po[2]}", 'views/single-member.php','GET']);
$r->set_route(["/members/{$po[2]}/edit", 'views/add-member.php','GET']);
$r->set_route(["/members/$po[2]/add-transaction", 'views/member-transaction.php','GET']);
$r->set_route(["/members/$po[2]/add-next-of-kin", 'views/add-next-of-kin.php','GET']);
$r->set_route(['/add-group', 'views/add-group.php','GET']);
$r->set_route(['/add-next-of-kin', 'views/add-next-of-kin.php', 'GET']);
$r->set_route(['/add-week', 'views/add-week.php', 'GET']);
$r->set_route(['/weeks', 'views/weeks.php', 'GET']);
$r->set_route(["/weeks/{$po[2]}/edit", 'views/add-week.php', 'GET']);
$r->set_route(['/groups', 'views/groups.php', 'GET']);
$r->set_route(["/groups/{$po[2]}/edit", 'views/edit-group.php', 'GET']);
$r->set_route(["/groups/{$po[2]}", 'views/single-group.php', 'GET']);
$r->set_route(["/groups?{$query_string}", 'views/add-week.php', 'GET']);
$r->set_route(['/logout', 'views/logout.php', 'GET']);
$r->set_route(['/ledgers', 'views/ledgers.php', 'GET']);
$r->set_route(["/ledgers/{$po[2]}", 'views/ledger-details.php', 'GET']);
$r->set_route(["/add-ledger", "views/add-ledger.php", "GET"]);
$r->set_route(['/loans', 'views/all-loans.php', "GET"]);
$r->set_route(["/loans/{$po[2]}", 'views/single-loan.php', "GET"]);
$r->set_route(["/loans/{$po[2]}/add-guaranter", 'views/add-guaranter.php', "GET"]);
$r->set_route(["/add-loan", "views/add-loan.php", "GET"]);
$r->set_route(["/admin", "views/all-admins.php", "GET"]);
$r->set_route(["/admin?{$query_string}", "views/profile.php", "GET"]);
$r->set_route(["/admin/{$po[2]}/edit", "views/add-user.php", "GET"]);
$r->set_route(["/add-admin", "views/add-user.php", "GET"]);
$r->set_route(['/make-transaction', "views/transaction.php", "GET"]);
$r->set_route(["/groups/{$po[2]}/make-transactions", "views/make-transactions.php", "GET"]);
$r->set_route(["/loans/{$po[2]}/loan-payment", "views/loan-payment.php", "GET"]);
$r->set_route(["/verifier/{$po[2]}", "views/from-mail.php", "GET"]);
$r->set_route(["/reports", "views/reports.php", "GET"]);
$r->set_route(["/transaction-reports", 'views/transaction-reports.php', 'GET']);
$r->set_route(["/loan-reports", 'views/loan-reports.php', 'GET']);
$r->set_route(["/weeks/{$po[2]}/week-transactions","views/week-transaction.php", "GET"]);
$r->set_route(["/logs", "views/logs.php", "GET"]);
$r->set_route(["/myprofile", "views/logs.php", "GET"]);
$r->set_route(["/logs?{$query_string}", "views/logs.php", "GET"]);

// $r->set_route(["/alan", "views/alan.php", "GET"]);
// $r->set_route(['/members'])
foreach($r->get_routes() as $route){ 
    
    if(strcmp("{$route[0]}",$url)==0){
        $ty = explode('/',"/{$route[0]}");
        $_GET['id'] = isset($ty[2])?$ty[3]:null;
        if( $route[2]==$meth){
            require_once $route[1];
            include_once "views/incs/foot.php";
            die();
        }else{
            die(json_encode(["error"=>"Method not allowed, expected method is {$route[2]}"]));
        }
        
    }
}
require_once "views/404.php";
include_once "views/incs/foot.php";
