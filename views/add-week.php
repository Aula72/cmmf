<?php 
$code = $help->get_last_id('w_id','weeks')+1;
$code = "W".$code;
?>
<div class="row">
	<form id="addWeek">
		    <div class="input-field col s12">
          <input id="code" type="text" value="<?php echo $code;?>" class="validate" disabled>
          <label for="code">Code</label>
        </div>
        <div class="input-field col s12 browser-default">
          <select id="g_id" class="validate" required>
          <option value="" disabled selected>Choose your option</option>
		      </select>
		    <label>Group</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center" type="submit" name="action">Add Week
    <i class="material-icons right">send</i>
  </button>
  </div>
	</form>
</div>

<script>
  $('#addWeek').submit(e=>{
    e.preventDefault();
    // alert($("#g_id").val())
    
    $.ajax({
      type: "post",
      url: `${base_url}/api/weekAPI.php`,
      data: JSON.stringify({
        group: $("#g_id").val(),
        week: $("#code").val()  
      }),    
      headers:{
        "auth": localStorage.getItem('token'),
        "content-type":"application/json",
        "accept":"*/*"
      },
      dataType: "json",
      success: function (response) {
        // console.log(response)
        if(response.status==1){
          setTimeout(() => {
            window.location = "/add-week";
          }, xtime);
          Materialize.toast(response.message, xtime);
          
        }else{

        }
      }
    });
  })
  $(document).ready(()=>{
  $.get(`${base_url}/api/groupAPI.php`, (data, status)=>{
    let c = JSON.parse(data);
    let m = ''
    console.log(c.group)
    
    for(var x of c.group){
      $('#g_id').append(`<option value="${x.g_id}">${x.g_code} (${x.g_name})</option>`);
      
    }
    
      
    })
  })
  
</script>
