<?php 
$code = $help->get_last_id('g_id','grouping')+1;
// $code = "G".$code;
?>
<div class="row">
	<form id="addGroup">
		<div class="row">
			<div class="input-field col s12">
	          <input id="name" type="text" class="validate" required>
	          <label for="name">Group Name</label>
	        </div>
		
			<div class="input-field col s12">
	          <input id="loc" type="text" class="validate" required>
	          <label for="loc">Group Location</label>
	        </div>
			<div class="input-field col s12">
	          <input id="code" type="text" value="" class="validate" required>
	          <label for="code">Group Code</label>
	        </div>
		</div>
		<div class="col s12 align-center">
		  	<button type="submit" class="btn waves-effect waves-light green" type="submit" name="action">Add Group
		    <i class="material-icons right">send</i>
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
	    name: $('#name').val()   
    }),
    headers:headers,
    dataType: "json",
    success: function (response) {
      console.log(response)
      if(response.status==1){
        toast(response.message)
        setTimeout(() => {
          
          window.location = "/groups";
        }, xtime);
      }else{
        toast(response.error)
      }
    }
  });
  })
  page_title('Add Group');
</script>
