<div class="valign-wrapper center-align">
<div class="row">
<img class="responsive-img" src="assets/img/pin.png">
    <form class="col s12" id="pinForm">
      <div class="row">
        <div class="input-field col s12">
          <i class="material-icons prefix">password</i>
          <input id="pin" type="password" autocomplete="false" class="validate" required>
          <label for="pin">Enter OTP</label>
        </div>
        </div>
        <button type="submit" class="waves-effect waves-light btn green" id="old_otp">Continue...</button>
        <button  class="waves-effect waves-light btn red" onclick="new_otps()" id="new_otp" style="display:none;">Request OTP...</button>
      </div>
    </form>
</div>
</div>

<script>
  page_title('Verify OTP');
  $('#pinForm').submit(e=>{
    e.preventDefault();
    // alert("hehe")
    //alert(JSON.stringify({otp:$('#pin').val(),mail:localStorage.getItem("mail")}))
    $.post(`${base_url}/api/otp.php`,JSON.stringify({otp:$('#pin').val(),mail:localStorage.getItem("mail")}), (data, status)=>{
      // alert(data);
      // let p = JSON.parse(data);
      if(data.status==1){
        localStorage.setItem("token",data.token);
        toast(data.message);
        setTimeout(() => {
          window.location = "/";
          // localStorage.removeItem("otp")
        }, xtime);        
      }else if(data.status==2){
        toast(data.message);
        $("#pin").val("");
        $("#pin").removeAttr("required");
        $("#pin").removeAttr("type");
        $("#old_otp").hide();
        $("#new_otp").show();
      }else{
        toast(data.message);
      }
    })
  })
  

  const new_otps = ( ) =>{
    $.ajax({
      type: "post",
      url: `${base_url}/api/user_login.php`,
      data: JSON.stringify({uname:user_mail}),
      dataType: "json",
      headers,
      success: function (response) {
        // console.log(response)
        if(response.status==1){
          toast(response.message, xtime)
          $("#old_otp").show();
          $("#new_otp").hide();
          $("#pin").attr("required", true);
          $("#pin").attr("type", "submit");
          
        }else{
          toast(response.message, xtime)
        }
        

      }
    });
  }
</script>