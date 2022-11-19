<?php 
$t = explode("/", $_SERVER["REQUEST_URI"]);
$code = $help->get_last_id('m_id','group_member')+1;
$m_code = 'M'.$code;

$d = intval(date('Y'));
$do = ($d-20)."-01-01";
$do1 = ($d-80)."-01-01";

$member = $help->query("select * from group_member where m_id=:m", [":m"=>$t[2]]);
$member = $member->fetch(\PDO::FETCH_ASSOC);
if(isset($t[2])){
  $mcode = $member["m_code"];
  $fname = $member["m_fname"];
  $lname = $member["m_lname"];
  // $email = $member["m_email"];
  $dob = $member["m_dob"];
  $phone = $member["m_phone"];
  $nin = $member["m_nin"];
  $sex = $member["m_gender"];
}else{
  $mcode = null;
}
?>
<h4 class="center-align">Add New Member</h4>
<div class="row">
	<form class="col s12" id="addMember">
		<!-- <div class="row"> -->
      <div class="input-field col s12">
          <input id="mcode" type="text" data-length='3' value="<?php echo $mcode; ?>" class="validate" >
          <label for="fname">No. </label>
        </div>
        <div class="input-field col s12">
          <input id="fname" type="text" value="<?php echo $fname; ?>" class="validate">
          <label for="fname">First Name</label>
        </div>
      <!-- </div> -->
      <!-- <div class="row"> -->
        <div class="input-field col s12">
          <input id="lname" type="text" value="<?php echo $lname; ?>" class="validate">
          <label for="lname">Second Name</label>
        </div>
      <!-- </div> -->
      <!-- <div class="input-field col s12">
          <input id="email" type="email" value="<?php echo $email; ?>" class="validate">
          <label for="email">Email Address</label>
        </div> -->
       <div class="input-field col s12">
          <input id="phone" type="number" value="<?php echo $phone; ?>" data-length="10" class="validate">
          <label for="phone">Phone Number</label>
        </div>

        <div class="input-field col s12">
          <input id="nin" type="text"  value="<?php echo $nin; ?>" data-length='14' class="validate">
          <label for="nin">NIN</label>
        </div>
		<div class="input-field col s12">
	    <select id="gender" value="<?php echo $sex; ?>">
	      <option value="" disabled selected>Choose your option</option>
	      <option value="1">Male</option>
	      <option value="2">Female</option>
	      
	    </select>
	    <label>Gender</label>
	  </div>
	  <div class="input-field col s12">
          <input id="dob" type="date" class="validate" value="<?php echo $dob; ?>" max="<?php echo $do; ?>" min="<?php echo $do1;?>">
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
    <input type="hidden" name="" id="edit_me" value="<?php echo $t[2]; ?>">
	  <div class="col s12 align-center">
      <?php if(isset($t[2])){?>
        <button class="btn waves-effect waves-light align-center green" type="submit" name="action">Update Member
    <i class="material-icons right">send</i>
  </button>
      <?php }else{?>
  	<button class="btn waves-effect waves-light align-center green" type="submit" name="action">Add Member
    <i class="material-icons right">send</i>
  </button>
        <?php } ?>
  </div>
  </form>
</div>

<script type="text/javascript">
  page_title('Add Member');
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
      edit_me: $("#edit_me").val()
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