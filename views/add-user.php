<?php 
$t = explode("/", $_SERVER["REQUEST_URI"]);
$m = isset($t[2])?"Edit User":"Add New User";

if(isset($t[2])){
  $user4 = $help->query("select * from user where user_id=:id", [":id"=>$t[2]]);
  $user4 = $user4->fetch(\PDO::FETCH_ASSOC);
  $fname = $user4["fname"];
  $lname = $user4["lname"];
  $mail = $user4["mail"];
  $status = $user4["status"];
  $types  = $user4["user_type_id"];
}else{
  $fname = null;
  $lname = null;
  $mail = null;
  $status = null;
  $types  = null;
}
?>
<div class="row">
<!-- <h4 class="center-align"><?php echo $m; ?></h4> -->
    <form class="col s12" id="addUser">
      <div class="row">
        <!-- <div class="input-field col s12">
          <input id="first_name" type="text" value="<?php echo $fname; ?>" class="validate">
          <label for="first_name">First Name</label>
        </div> -->
        <div id="fname-div"></div>
        <!-- <div class="input-field col s12">
          <input id="last_name" type="text" value="<?php echo $lname; ?>" class="validate">
          <label for="last_name">Last Name</label>
        </div> -->
        <div id="lname-div"></div>
        <!-- <div class="input-field col s12">
          <input id="mail" type="email" value="<?php echo $mail; ?>" class="validate">
          <label for="mail">Email</label>
        </div> -->
        <div id="mail-div"></div>
        <div id="type-div"></div>
        <div id="act-div"></div>
        <input type="hidden" name="" id="status" value="<?php echo $status; ?>">
        <input type="hidden" name="" id="edit_user" value="<?php echo $t[2]; ?>">
      </div>
      <!-- <div class="row"> -->
        
  <button class="btn btn-outline-success rounded-pill" type="submit" name="action">Submit
    <i class="bi bi-send"></i>
  </button>
        
      <!-- </div> -->
    </form>
  </div>

  <script>
    let m = "<?php echo $m; ?>"
    page_title(`${m}`);
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
                types: $("#types").val(),
                edit_user:$("#edit_user").val()
            }),
            headers,
            dataType: "json",
            success: function (response) {
                console.log(response)
                if(response.status){
                    toast(response.message, xtime)
                    setInterval(() => {
                        window.location = "/admin";
                    }, xtime);
                }else{
                    toast(response.error, xtime)
                }

            }
        });
    })

    let ad  = []
    $.ajax({
      type: "get",
      url:  `${base_url}/api/userAPI.php?type`,
      headers,
      dataType: "json",
      success: function (response) {
        // console.log(response)
        try{
          for(let m of response.types){
            // console.log(m)
            ad.push(m)
          }
          Select({div:"type-div", id:"types", value:"<?php echo $types; ?>", label:"User Type", options:ad,});
        }catch(TypeError){
          logout();
        }
      }
    });
    // console.log(ad)
    Input({div:"fname-div", id:"first_name", value:"<?php echo $fname; ?>", label:"First Name"})
    Input({div:"lname-div", id:"last_name", value:"<?php echo $lname; ?>", label:"Lst Name"})
    Input({div:"mail-div", id:"mail", value:"<?php echo $mail; ?>", label:"Email Address", type:"email"})
    
    Select({div:"act-div", id:"status", value:"<?php echo $status; ?>", label:"Status",options:[{value:1, title:"Active"},{value:2, title:"In-Active"}]})

    allow_url([1,3])
  </script>


        