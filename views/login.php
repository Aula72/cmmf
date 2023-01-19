
    
      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block"><?php echo APP_NAME; ?></span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                    <p class="text-center small">Enter your Email Address</p>
                  </div>

                  <form class="row g-3 needs-validation" id="login" novalidate>

                    <div class="col-12">
                      <!-- <label for="uname" class="form-label">Email</label> -->
                      <div class="input-group has-validation">
                        <!-- <span class="input-group-text" id="inputGroupPrepend">@</span> -->
                        <input type="text" name="username" placeholder="Email Address" class="form-control rounded-pill" id="m" required>
                        <div class="invalid-feedback">Please enter your Email Address.</div>
                      </div>
                    </div>

                    <!-- <div class="col-12">
                      <label for="yourPassword" class="form-label">Password</label>
                      <input type="password" name="password" class="form-control" id="yourPassword" required>
                      <div class="invalid-feedback">Please enter your password!</div>
                    </div> -->

                    <!-- <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="true" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                      </div>
                    </div> -->
                    <div class="col-12">
                      <button class="btn btn-outline-success rounded-pill w-100" type="submit">Get OTP</button>
                    </div>
                    <!-- <div class="col-12">
                      <p class="small mb-0">Don't have account? <a href="pages-register.html">Create an account</a></p>
                    </div> -->
                  </form>

                </div>
              </div>

              
            </div>
          </div>
        </div>

      </section>

    

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
        localStorage.setItem('full_name', data.full_name);
        localStorage.setItem('long_name', data.long_name);
        localStorage.setItem("utype", data.utype);
        toast(data.message)
        setTimeout(function(){
          console.log(data.otp)
          // localStorage.setItem("otp", "yes")
          window.location = "/otp"
        },xtime);
        if(window.location.host!="cmmf.fueless.co.ug"){
          alert(data.otp)
        }
        
      }else{
        toast(data.message, 'danger')
        
      }
    })
  })
  $("#p_title").css({display:"none"});
  $("main").removeAttr("class");
  $("main").removeAttr("id");
</script>