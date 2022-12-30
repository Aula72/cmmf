
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
                    <p class="text-center small">Enter your OTP</p>
                  </div>

                  <form class="row g-3 needs-validation" id="pinForm" novalidate>

                    <div class="col-12">
                      <!-- <label for="uname" class="form-label">OTP</label> -->
                      <div class="input-group has-validation">
                        <!-- <span class="input-group-text" id="inputGroupPrepend">@</span> -->
                        <input type="password" placeholder="Enter OTP..." name="username" class="form-control rounded-pill" id="pin" required>
                        <!-- <div class="invalid-feedback">Please enter your Email Address.</div> -->
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
                      <button class="btn btn-outline-success rounded-pill w-100" type="submit">Verify OTP</button>
                    </div>
                    <div class="col-12">
                      <p class="small mb-0">Forgot Me OTP? <a href="/login">Generate New One...</a></p>
                    </div>
                  </form>

                </div>
              </div>

              
            </div>
          </div>
        </div>

      </section>

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
  $("#p_title").css({display:"none"});
  $("main").removeAttr("class");
  $("main").removeAttr("id");
</script>