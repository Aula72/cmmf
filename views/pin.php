<div class="valign-wrapper center-align">
<div class="row">
<img class="responsive-img" src="assets/img/pin.png">
    <form class="col s12" id="pinForm">
      <div class="row">
        <div class="input-field col s12">
          <i class="material-icons prefix">password</i>
          <input id="pin" type="password" class="validate" required>
          <label for="pin">Enter OTP</label>
        </div>
        </div>
        <button type="submit" class="waves-effect waves-light btn green">Continue...</button>
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
      }else{
        toast(data.message);
      }
    })
  })
  
</script>