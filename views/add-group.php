<?php 
$code = $help->get_last_id('g_id','grouping')+1;
// $code = "G".$code;
?>
<div class="row">
  <!-- <h4 class="center-align">Add New Group</h4> -->
	<form id="addGroup">
		<div class="row">
    <div class="row mb-3">
        <label for="name" class="col-sm-2 col-form-label">Group Name</label>
        <div class="col-sm-10">
          <input type="text" id="name" class="form-control rounded-pill" required>
        </div>
      </div>
			<div class="row mb-3">
        <label for="loc" class="col-sm-2 col-form-label">Group Location</label>
        <div class="col-sm-10">
          <input type="text" id="loc" class="form-control rounded-pill " required>
        </div>
      </div>
		
			<div class="row mb-3">
        <label for="inputText" class="col-sm-2 col-form-label">Group Code</label>
        <div class="col-sm-10">
          <input type="text" id="code" class="form-control rounded-pill" required>
          <span class="text-danger mb-3" style="text-align:center; font-size: 10px;" id="wcode"></span>
        </div>
      </div>
			
		<div class="col s12 align-center">
		  	<button type="submit" class="btn btn-secondary rounded-pill" type="submit" name="action">Add Group
		    <i class="bi bi-send"></i>
		  </button>
		</div>
	</form>
</div>


<script>
  $('#addGroup').submit(e=>{
    e.preventDefault();
    // alert($("#g_id").val())
    $.ajax({
    type: "post",
    url: `${base_url}/api/groupAPI.php`,
    data: JSON.stringify({
      location: $('#loc').val(),
	    name: $('#name').val(),
      code: $('#code').val()   
    }),
    headers:headers,
    dataType: "json",
    success: function (response) {
      // console.log(response)
      try{
        if(response.status==1){
          toast(response.message)
          setTimeout(() => {          
            window.location = "/groups";
          }, xtime);
        }else{
          toast(response.error, 'danger')
        }
      }catch(TypeError){
        logout();
      }
    }
  });
  })
  page_title('Add Group');
  allow_url([2])

  $("#code").on("input", e=>{
    console.log($("#code").val())
    let c = $("#code").val()
    $.ajax({
      type: "get",
      url: `${base_url}/api/groupAPI.php?check=${c}`,
      headers,
      dataType: "json",
      success: function (response) {
        console.log(response.checks)
        if(c.length>0){
          if(response.checks.length){
            $("#wcode").show()
            $("#wcode").text(`Group with code ${c} already exists, try another one...`);
            $("button:submit").attr("disabled", true);
          }else{
            $("#wcode").hide()
            $("button:submit").removeAttr("disabled");
          }
        }else{
          $("#wcode").hide()
        }
      }
    });
  })
</script>
