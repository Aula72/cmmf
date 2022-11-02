<?php 
$code = $help->get_last_id('m_id','group_member')+1;
$m_code = 'M'.$code;
?>
<div class="row">
	<form id="addMember">
		<!-- <div class="row"> -->
      <div class="input-field col s12">
          <input id="mcode" type="text" value="<?php echo $m_code; ?>" class="validate" disabled>
          <label for="fname">Member Code</label>
        </div>
        <div class="input-field col s12">
          <input id="fname" type="text" class="validate">
          <label for="fname">First Name</label>
        </div>
      <!-- </div> -->
      <!-- <div class="row"> -->
        <div class="input-field col s12">
          <input id="lname" type="text" class="validate">
          <label for="lname">Second Name</label>
        </div>
      <!-- </div> -->
      <div class="input-field col s12">
          <input id="email" type="email" class="validate">
          <label for="email">Email Address</label>
        </div>
       <div class="input-field col s12">
          <input id="phone" type="tel" class="validate">
          <label for="phone">Phone Number</label>
        </div>

        <div class="input-field col s12">
          <input id="nin" type="text" class="validate">
          <label for="nin">NIN</label>
        </div>
		<div class="input-field col s12">
	    <select id="gender">
	      <option value="" disabled selected>Choose your option</option>
	      <option value="1">Male</option>
	      <option value="2">Female</option>
	      
	    </select>
	    <label>Gender</label>
	  </div>
	  <div class="input-field col s12">
          <input id="dob" type="date" class="validate">
          <label for="dob">Date of Birth</label>
        </div>
		<!-- <div class="input-field col s12">
	    <select id="grp" class="browser-default">
	      <option value="" disabled selected>Choose your option</option>
	      <option value="1">Option 1</option>
	      <option value="2">Option 2</option>
	      <option value="3">Option 3</option>
	    </select>
	    <label>Group</label>
	  </div> -->
	  <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center" type="submit" name="action">Add Member
    <i class="material-icons right">send</i>
  </button>
  </div>
  </form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
    // $('select').formSelect();
    // Old way
    // $('select').material_select();
});

$("#addMember").submit(e=>{
  e.preventDefault();
  $.ajax({
    type: "post",
    url: `${base_url}/api/groupMemberAPI.php`,
    headers:headers,
    data: JSON.stringify({
      mcode:$('#mcode').val(),
      fname:$('#fname').val(),
      lname:$('#lname').val(),
      phone:$('#phone').val(),
      nin:$('#nin').val(),
      gender:$('#gender').val(),
      dob:$('#dob').val(),
      grp:localStorage.getItem('g_id'),
    }),
    dataType: "json",
    success: function (response) {
      console.log(response)
      // Materialize.toast(response.error, xtime);
      // let c = JSON.parse(response);
      if(response.status == 1){
        Materialize.toast(response.message, xtime);
        setTimeout(() => {
          window.location = `/groups/${localStorage.getItem('g_id')}`
        }, xtime);
      }else{
        Materialize.toast(response.error, xtime);
      }
    }
  });
})
</script>