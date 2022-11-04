<div class="valign-wrapper center-align">
  <div class="row">
      <img class="responsive-img" src="assets/img/login.png">
      <form class="col s12" id="login">
        
        <div class="row">
          
          <div class="input-field col s12">
            <i class="material-icons prefix">email</i>
            <input id="m" type="text" class="validate" required>
            <label for="icon_prefix">Enter Email...</label>
          </div>
          <!-- </div> -->
          <button class="waves-effect waves-light btn">Login...</button>
        </div>
        
      </form>
      </div>
</div>

<script type="text/javascript">
  page_title('Login to continue...');
  $(document).ready(function () {
    localStorage.clear();
  });
  $("#login").submit(e=>{
    e.preventDefault();
   
    let d = {"uname":$('#m').val()}
    $.post(`${base_url}/api/user_login.php`, JSON.stringify(d), (data,status)=>{
      if(data.status==1){
        localStorage.setItem('mail', $('#m').val())
        toast(data.message)
        setTimeout(function(){
          // console.log(data.otp)
          window.location = "/otp"
        },xtime);
        alert(data.otp)
      }else{
        toast(data.message)
        
      }
    })
  })
</script>

