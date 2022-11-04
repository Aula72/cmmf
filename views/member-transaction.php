<?php 
$t = explode("/",$_SERVER['REQUEST_URI']);
// $members = $help->query("select * from group_member");
$ledgers = $help->query("select * from trans_types");
$code = $help->get_last_id("t_id", "trans_action")+1;
$code = "TRANS".$code."CMMF";
?>
<div class="row">
	<form id="addTransaction">
		<input type="hidden" name="" id="m_id" value="<?php echo $t[2]; ?>">
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
            if(response.status){
                toast(response.message)
                setTimeout(() => {
                    window.location  = `/members/${$("#m_id").val()}`
                }, xtime);
            }else{
                toast(response.error)
            }
        }
    });
});
</script>