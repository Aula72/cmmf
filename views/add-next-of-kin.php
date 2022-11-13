<?php 
$t = explode("/",$_SERVER['REQUEST_URI']);
?>
<div class="row">
	<form class="col s12" id="addNextOfKin">
		<!-- <div class="row"> -->
      <input type="hidden" name="" id="member" value="<?php echo $t[2]; ?>">
        <div class="input-field col s12">
          <input id="fname" type="text" class="validate">
          <label for="fname">First Name</label>
        </div>
      <!-- </div> -->
      <!-- <div class="row"> -->
        <div class="input-field col s12">
          <input id="sname" type="text" class="validate">
          <label for="sname">Second Name</label>
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
	    <select id="gender" class="validate">
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
		<div class="input-field col s12">
          <input id="location" type="text" class="validate">
          <label for="location">Location</label>
        </div>
	  <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center" type="submit" name="action">Add Next Kin
    <i class="material-icons right">send</i>
  </button>
  </div>
  </form>
</div>

<script type="text/javascript">
  page_title('Add Next of Kin');
  $("#addNextOfKin").submit(function (e) { 
    e.preventDefault();
    $.ajax({
    url: `${base_url}/api/nextOfKinAPI.php`,
    method: "post",
    data: JSON.stringify({
      dob:$('#dob').val(),
      location: $('#location').val(),
      gender: $('#gender').val(),
      location: $('#location').val(),
      location: $('#location').val(),
      location: $('#location').val(),
      location: $('#location').val(),
    }),
    headers,
    dataType: "json",
    success: function (response) {
      if(response.status){
        toast(response.message)
        setTimeout(() => {
          window.location = `/members/${$('#member').val()}`
        }, xtime);
      }else{
        toast(response.error);
      }
    }
  });
  });
	
</script>