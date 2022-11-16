<div class="valign-wrapper center-align">
  <div class="row">
      <!-- <div class="col l6 push-l5"> -->
          <img class="responsive-img " src="assets/img/login.png">
      <!-- </div> -->
      <!-- <div class="col l6 pull-l5"> -->
      <form class="col s12 " id="login">
        
        <div class="row">
          
          <div class="input-field col s12">
            <i class="material-icons prefix">email</i>
            <input id="m" type="email" autocomplete="false" class="validate" required>
            <label for="icon_prefix">Enter Email...</label>
          </div>
          <!-- </div> -->
          <br>
          <button class="waves-effect waves-light btn green">Login...</button>
        </div>
        
      </form>
<!-- </div> -->
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
          // localStorage.setItem("otp", "yes")
          window.location = "/otp"
        },xtime);
        if(window.location.host!="cmmf.fueless.co.ug"){
          alert(data.otp)
        }
        
      }else{
        toast(data.message)
        
      }
    })
  })
</script>

