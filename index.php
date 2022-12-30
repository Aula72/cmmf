<?php 
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// die(json_encode(["err"=>$_SERVER]));
// die(json_encode(["err"=>$_SERVER['SERVER_PROTOCOL']]));
define("APP_NAME","CMMF");

include_once 'views/incs/head.php';
include_once 'views/incs/nav.php'; 
include_once 'views/incs/print_head.php';
?>
<script>
	let base_url = "<?php echo isset($_SERVER['HTTPS'])?'https':'http'; ?>://<?php echo $_SERVER['HTTP_HOST']?>";
</script>
<script src="<?php echo isset($_SERVER['HTTPS'])?'https':'http'; ?>://<?php echo $_SERVER['HTTP_HOST']?>/general.js"></script>


<style>
	#toast-container {
		top: 0 !important;
		/* right: auto !important; */
		bottom: 10%;
		/* left:7%;   */
		border-radius: 50% !important;
		/* background-color: yellow !important; */
		/* text-align: justify; */
	}
	#loader {
		position: absolute;
		top :0;
		left: 0;
		right: 0;
		bottom: 0;
		margin: auto;
		display:none;
	}
</style>
<?php 
require_once "views/incs/load.php";
require_once "controllers/Routes.php";
require_once "config/constants.php";
require_once "config/db.php";
require_once "controllers/Helpers.php";

$url = $_SERVER['REQUEST_URI'];
$meth = $_SERVER['REQUEST_METHOD'];

$r = new \Cmmf\Route;
$help = new \Cmmf\Helper; 


// $help->mail_send(12356, "moncytod@gmail.com");

// die(json_encode(["url"=>$url]));
if(stripos('api', $url)){
	require_once "api_routes.php";
}else{
	require_once "routes.php";
}


