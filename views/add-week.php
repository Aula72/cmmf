<?php 
$code = $help->get_last_id('w_id','weeks')+1;
$code = "W".$code;

$grp = $help->query("select * from grouping");
$year = $help->query("select * from finanial_year order by y_id desc");
?>
<div class="row">
<!-- <h4 class="center-align">Add Week</h4> -->
	<form class="col s12" id="addWeek">
      <div class="row mb-3">
        <label for="inputText" class="col-sm-2 col-form-label">Code</label>
        <div class="col-sm-10">
          <input type="text" id="code" class="form-control rounded-pill">
        </div>
      </div>
		    
        

        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Financial Year</label>
          <div class="col-sm-10">
            <select class="form-select rounded-pill" id="year" aria-label="Default select example">
              <option selected>Open this select menu</option>
              <?php 
                foreach($year->fetchAll() as $row){
                  echo "<option value=".$row['y_id'].">".$row["name"]."</option>";
                }
              ?>
            </select>
          </div>
        </div>
        
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Group</label>
          <div class="col-sm-10">
            <select class="form-select rounded-pill" id="g_id" aria-label="Default select example">
              <option selected>Open this select menu</option>
              <?php 
                // var_dump($grp->fetchAll());
                foreach($grp->fetchAll() as $row){
                  echo "<option value=".$row['g_id'].">".$row["g_code"]."</option>";
                }
              ?>
            </select>
          </div>
        </div>
        <div class="row mb-3">
          <label for="w_date" class="col-sm-2 col-form-label">Start Date</label>
          <div class="col-sm-10">
            <input type="date" id="w_date" class="form-control rounded-pill">
          </div>
        </div>
        <div class="col s12 align-center">
  	<button class="btn btn-success rounded-pill" type="submit" name="action">Add Week
    <i class="bi bi-send"></i>
  </button>
  </div>
	</form>
</div>

<script>
  page_title('Add Week');
  $('#addWeek').submit(e=>{
    e.preventDefault();
    // alert($("#g_id").val())
    
    $.ajax({
      type: "post",
      url: `${base_url}/api/weekAPI.php`,
      data: JSON.stringify({
        group: $("#g_id").val(),
        code: $("#code").val(),
        dat: $('#w_date').val(),
        year: $("#year").val()
      }),    
      headers:headers,
      dataType: "json",
      success: function (response) {
        // console.log(response)
        try{
          if(response.status==1){
            setTimeout(() => {
              window.location = "/add-week";
            }, xtime);
            toast(response.message);
            
          }else{
            toast(response.error);
          }
        }catch(TypeError){
          logout();
        }
      }
    });
  });
  

  
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

    allow_url([2])
  </script>