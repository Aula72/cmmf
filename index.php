<?php 
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
// die(json_encode(["err"=>$_SERVER]));
// die(json_encode(["err"=>$_SERVER['SERVER_PROTOCOL']]));
?>
<script>
	let base_url = "<?php echo isset($_SERVER['HTTPS'])?'https':'http'; ?>://<?php echo $_SERVER['HTTP_HOST']?>";
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

	const print_now = () =>{
		$('a.btn-floating').css({display:'none'})
		$('#nav').css({display:'none'})
		
		window.print();
		$('a.btn-floating').css({display:'inline-block'})
		$('#nav').css({display:'inline-block'})
	}
	//page load progress
	// let size = file.getSize() //size of file
	// const track_load_progress = () =>{
	// 	let loaded = file.getLoaded();
	// 	let p = parseInt(loaded/size)
	// 	$("#loader").css({display:"inline-block"})
	// 	if(p==100){
	// 		$("#loader").css({display:"none"})
	// 		setTimeout("track_load_progress()", 20);
	// 	}
	// }
	// track_load_progress();
	let nm = new Intl.NumberFormat("en-US")

	
	
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


