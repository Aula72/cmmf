<!-- <h4 class="center-align">CMMF Admin</h4> -->
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
}else{
  $fname = null;
  $lname = null;
  $mail = null;
  $status = null;
}
?>
<div class="row">
<div class="col-lg-8">
<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>  
            <th><a class="btn btn-outline-success btn-sm rounded-pill" href="add-admin" name="action">Add User
    <i class="bi bi-person-add"></i>
  </a></th>  
        <th>
            <button class="btn btn-sm btn-outline-primary rounded-pill" onclick="print_now();">Print <i class="bi bi-printer"></i></button>
        </th>       
        </tr>
    </thead>
    <tbody id="adminList">

    </tbody>
</table>
</div>



    
      
   
    <!-- <div class="row">
        <div class="col-lg-1">
         -->
        <!-- </div>
    </div> -->
  
        
      
  </div>
</div>



<script>
    page_title('Admin');
    // let bn = user_mail=="kibirigetwaha123@gmail.com"
    let bn = 'simo@gold.vom'
    const get_admins =() =>{
        $.ajax({
            type: "GET",
            url: `${base_url}/api/userAPI.php`,
            headers:headers,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                // alert(user_mail)
                try{
                    let x  = ''
                    for(let m of response.admin){
                        
                        x +=`<tr>
                            <td>${m.lname} ${m.fname}</td>
                            <td>${m.mail}</td>
                            <td>${m.status=='1'?'Active':'In-Active'}</td>
                            <td>${m.typn}</td>
                            <td ${bn?this.visibility='visible':this.visibility='hidden'}><a href="/admin/${m.user_id}/edit"><i class="bi bi-pencil"></i></a></td>
                            <td ${bn?this.visibility='visible':this.visibility='hidden'}><i class="bi bi-settings" onclick='change_status("${m.user_id}", "${m.mail}", "${m.status}")'></i></td>
                            
                            </tr>`;
                    }
                    $("#adminList").html(x)
                }catch(TypeError){
                    logout();
                }
            }
        });
    }
    // const do_nothing = () =>{

    // }
    // const loo = (a, b, c) =>{
    //     if(user_mail=="tkibirige@cmmf.com"){
    //         cpp(a, b, c)
    //     }
    // }
    get_admins();
    const change_status = (id, ma, s) =>{
        
        let  msg = `Activate ${ma}'s account`
        if(id!=1){
            if(s==1){
                msg = `Deactivate ${ma}'s account`
            }
        
        let x = confirm(msg);
        if(x){
            $.ajax({
            type: "get",
            url: `${base_url}/api/userAPI.php?status=${id}`,
            headers,
            dataType: "json",
            success: function (response) {
                if(response.status){
                    toast(response.message)
                    setTimeout(() => {
                        get_admins();
                    });
                }else{
                    toast(response.error, 'danger') 
                }
            }
    });
        }
        }
    
    }

    // const cpp =(a, b, c)=> {
    //     if(a==1){
    //         do_nothing()
    //     }else{
    //         change_status(a, b, c)
    //     }
    // }

    if(bn){
        $("#admin").show()
    }
    else{
        $("#admin").hide();
    }
</script>

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
                // if(response.status){
                    toast('Operation was successful...', xtime)
                    setInterval(() => {
                        window.location = "/admin";
                    }, xtime);
                // }else{
                //     Materialize.toast(response.error, xtime)
                // }

            }
        });
    })
  </script>


        