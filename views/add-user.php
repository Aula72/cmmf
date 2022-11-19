<?php 
$t = explode("/", $_SERVER["REQUEST_URI"]);
$m = isset($t[2])?"Edit User":"Add New User";
$user = $help->query("select * from user where user_id=:id", [":id"=>$t[2]]);
$user = $user->fetch(\PDO::FETCH_ASSOC);
if(isset($t[2])){
  $fname = $user["fname"];
  $lname = $user["lname"];
  $mail = $user["mail"];
  $status = $user["status"];
}else{
  $fname = null;
  $lname = null;
  $mail = null;
  $status = null;
}
?>
<div class="row">
<h4 class="center-align"><?php echo $m; ?></h4>
    <form class="col s12" id="addUser">
      <div class="row">
        <div class="input-field col s12">
          <input id="first_name" type="text" value="<?php echo $fname; ?>" class="validate">
          <label for="first_name">First Name</label>
        </div>
    
        <div class="input-field col s12">
          <input id="last_name" type="text" value="<?php echo $lname; ?>" class="validate">
          <label for="last_name">Last Name</label>
        </div>
      
        <div class="input-field col s12">
          <input id="mail" type="email" value="<?php echo $mail; ?>" class="validate">
          <label for="mail">Email</label>
        </div>
        <input type="hidden" name="" id="status" value="<?php echo $status; ?>">
        <input type="hidden" name="" id="edit_user" value="<?php echo $t[2]; ?>">
      </div>
      <div class="row">
        
  <button class="btn waves-effect waves-light green" type="submit" name="action">Submit
    <i class="material-icons right">send</i>
  </button>
        
      </div>
    </form>
  </div>

  <script>
    let m = "<?php echo $m; ?>"
    page_title(m);
    $("#addUser").submit(e=>{
        e.preventDefault();
        // console.log($("#addUser"))
        $.ajax({
            type: "post",
            url: `${base_url}/api/userAPI.php`,
            data: JSON.stringify({
                mail: $("#mail").val(),
                lname: $("#last_name").val(),
                fname: $("#first_name").val(),
                status:$("#status").val(),
                edit_user:$("#edit_user").val()
            }),
            headers:headers,
            dataType: "json",
            success: function (response) {
                if(response.status){
                    Materialize.toast(response.message, xtime)
                    setInterval(() => {
                        window.location = "/admin";
                    }, xtime);
                }else{
                    Materialize.toast(response.error, xtime)
                }
            }
        });
    })
  </script>


        