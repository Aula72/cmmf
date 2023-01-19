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
<!-- <h4 class="center-align">Add New Member</h4> -->
<div class="row">
	<form class="col s12" id="addMember">
		<!-- <div class="row"> -->
      <!-- <div class="row mb-3">
      <label class="col-sm-2 col-form-label" for="fname">No. </label>
      <div class="col-sm-10">
      
          <input class="form-control rounded-pill" id="mcode" type="text" data-length='3' value="<?php echo $mcode; ?>" class="validate" >
</div>  
          
        </div> -->
        <!-- <div class="row mb-3">
        <label class="col-sm-2 col-form-label" for="fname">First Name</label>
        <div class="col-sm-10">
          <input class="form-control rounded-pill" id="fname" type="text" value="<?php echo $fname; ?>" class="validate">
        </div>  
          
        </div> -->
        <div id="code-div"></div>
        <div id="fname-div"></div>
        <div id="lname-div"></div>
        <div id="phone-div"></div>
        <div id="nin-div"></div>
		
        <div id="gender-div"></div>
        <div id="dob-div"></div>
	 
    <input class="form-control rounded-pill" type="hidden" name="" id="edit_me" value="<?php echo $t[2]; ?>">
	  <div class="col s12 align-center">
      <?php if(isset($t[2])){?>
        <button class="btn btn-outline-success rounded-pill" type="submit" name="action">Update Member
    <i class="bi bi-send"></i>
  </button>
      <?php }else{?>
  	<button class="btn btn-outline-success rounded-pill" type="submit" name="action">Add Member
    <i class="bi bi-send"></i>
  </button>

  <div id="tb-div"></div>
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
      // console.log(response)
      // Materialize.toast(response.error, xtime);
      // let c = JSON.parse(response);
      try{
        if(response.status == 1){
          toast(response.message);
          setTimeout(() => {
            window.location = `/groups/${localStorage.getItem('g_id')}`
          }, xtime);
        }else{
          toast(response.error, 'danger');
        }
      }catch(TypeError){
        logout();
      }
    }
  });
})

// Table({div:"tb-div", head:["no.", 'gender', '<button class="btn btn-success">Print</button>',''], body:[[1, 3, 'Aula'],[4,7, '<button class="btn btn-success">Print</button>']]})
Select({div:"gender-div", id:"gender", label:"Gender", value:"<?php echo $sex; ?>", options:[{value:1, title:"Male",},{value:2, title:"Female"}]})
Input({div:"dob-div", id:"dob", label:"Date of Birth", type:"date", value:"<?php echo $dob; ?>"})
Input({div:"code-div", id:"mcode", label:"No.", value:"<?php echo $mcode; ?>"})
Input({div:'fname-div', id:"fname", type:"text", label:"First Name", value:"<?php echo $fname; ?>"})
Input({div:"lname-div", id:"lname", type:"text", label:"Last Name", value:"<?php echo $lname; ?>"})
Input({div:"phone-div", id:"phone", type:"tel", label:"Phone Number", value:"<?php echo $phone; ?>", });
Input({div:"nin-div", id:"nin", type:"text", label:"NIN", value:"<?php echo $nin; ?>"})

allow_url([2])
</script>