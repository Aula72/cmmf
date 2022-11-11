<?php 
$code = $help->get_last_id('w_id','weeks')+1;
$code = "W".$code;

$grp = $help->query("select * from grouping");
?>
<div class="row">
	<form id="addWeek">
		    <div class="input-field col s12">
          <input id="code" type="text" value="" class="validate">
          <label for="code">Code</label>
        </div>
        <div class="input-field col s12 browser-default">
          <select id="g_id" name="g_id" class="validate">
          <option value="" disabled selected>Choose your option</option>
          <?php 
            foreach($grp->fetchAll() as $row){
              echo "<option value=".$row['g_id'].">".$row["g_code"]."</option>";
            }
          ?>
		      </select>
		    <label>Group</label>
        </div>
        <div class="input-field col s12">
          <input id="w_date" type="date" value="" class="validate">
          <label for="w_date">Date</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center green" type="submit" name="action">Add Week
    <i class="material-icons right">send</i>
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
      }),    
      headers:headers,
      dataType: "json",
      success: function (response) {
        // console.log(response)
        if(response.status==1){
          setTimeout(() => {
            window.location = "/add-week";
          }, xtime);
          toast(response.message);
          
        }else{
          toast(response.error);
        }
      }
    });
  });
  

  
</script>
