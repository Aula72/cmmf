<?php 
$members = $help->query("select * from group_member");
$ledgers = $help->query("select * from trans_types");
$code = $help->get_last_id("t_id", "trans_action")+1;
$code = "TRANS".$code."CMMF";
?>
<div class="row">
<h4 class="center-align">Make Transaction</h4>
	<form id="addTransaction">
		<div class="input-field col s12">
          <select  id="m_id" name="m_id" >
            <option value="" selected>Select Member</option>
            <?php
                foreach($members->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['m_id'].">".$row['m_fname']." ".$row['m_lname']."  (".$row['m_code'].")</option>";
                }
            ?>
          </select>
          <label for="name">Member's Details</label>
        </div>
        <div class="input-field col s12">
          <select  id="trans_type" name="trans_type" >
          <option value="" selected>Select Ledger Type</option>
            <?php
                foreach($ledgers->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['ty_id'].">".$row['ty_name']." (x".$row['mult'].")</option>";
                }
            ?>
          </select>
          <label for="name">Transaction Type</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="number"  id="t_amount" data-length="7" required>
          <label for="t_amount">Amount</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="text" value="<?php echo $code;?>" id="t_code" disabled>
          <label for="t_code">Code</label>
        </div>
        <div class="input-field col s12 browser-default">
            <textarea  name="" id="t_desc" class="materialize-textarea" cols="30" rows="5"></textarea>
            <label for="t_desc">Comment</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center" type="submit" name="action">Add Transaction
    <i class="material-icons right">send</i>
  </button>
  </div>
	</form>
</div>

<script>
    page_title('New Transaction');
    $("#addTransaction").submit(e=>{
        e.preventDefault();
   
    $.ajax({
        type: "post",
        url: `${base_url}/api/transactionAPI.php`,
        data: JSON.stringify({
            "member":$("#m_id").val(),
            "code":$("#t_code").val(),
            "trans_type":$("#trans_type").val(),
            "comment":$("#t_desc").val(),
            "amount":$("#t_amount").val()
        }),
        headers:headers,
        dataType: "json",
        success: function (response) {
            // console.log(response)
            try{
              if(response.status){
                  toast(response.message, xtime)
                  setTimeout(() => {
                      window.location  = "/make-transaction"
                  }, xtime);
              }else{
                  toast(response.error, xtime)
              }
            }catch(TypeError){
              logout();
            }
        }
    });
});
</script>