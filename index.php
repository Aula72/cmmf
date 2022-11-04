<?php 
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
?>
<script>
	let base_url = "http://<?php echo $_SERVER['HTTP_HOST']?>";
	let token = localStorage.getItem("token");
	let user_mail = localStorage.getItem("mail");
	let xtime = 5000;
	let headers = {
		"content-type":"application/json",
		"auth":token,
		"accept":"*/*",
	}
	// console.log(user_mail)
	const toast = (x) =>{
		Materialize.toast(`<p style="white-space:pre-wrap; word-break:break-word; text-align:center;">${x}</p>`, xtime);
	}
	const page_title = (title) =>{
		document.title = `${title} > CMMF`;
	}
</script>
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
</style>
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


