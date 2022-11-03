<div class="row">
    <form class="col s12" id="addUser">
      <div class="row">
        <div class="input-field col s12">
          <input id="first_name" type="text" class="validate">
          <label for="first_name">First Name</label>
        </div>
    
        <div class="input-field col s12">
          <input id="last_name" type="text" class="validate">
          <label for="last_name">Last Name</label>
        </div>
      
        <div class="input-field col s12">
          <input id="mail" type="email" class="validate">
          <label for="mail">Email</label>
        </div>
      </div>
      <div class="row">
        
  <button class="btn waves-effect waves-light" type="submit" name="action">Submit
    <i class="material-icons right">send</i>
  </button>
        
      </div>
    </form>
  </div>

  <script>
    page_title('Add User');
    $("#addUser").submit(e=>{
        e.preventDefault();
        $.ajax({
            type: "post",
            url: `${base_url}/api/userAPI.php`,
            data: JSON.stringify({
                mail: $("#mail").val(),
                lname: $("#last_name").val(),
                fname: $("#first_name").val()
            }),
            headers:headers,
            dataType: "json",
            success: function (response) {
                if(response.status){
                    Materialize.toast(response.message, xtime)
                    setInterval(() => {
                        window.location = "/add-admin";
                    }, xtime);
                }else{
                    Materialize.toast(response.error, xtime)
                }
            }
        });
    })
  </script>


        