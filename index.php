
<script>
	let base_url = "http://<?php echo $_SERVER['HTTP_HOST']?>";
	let token = localStorage.getItem("token");
	let xtime = 5000;

	
</script>
<?php 
require_once "controllers/Routes.php";
require_once "config/db.php";
require_once "controllers/Helpers.php";

$url = $_SERVER['REQUEST_URI'];
$meth = $_SERVER['REQUEST_METHOD'];

$r = new \Cmmf\Route;
$help = new \Cmmf\Helper;


// die(json_encode(["url"=>$url]));
if(stripos('api', $url)){
	require_once "api_routes.php";
}else{
	require_once "routes.php";
}


