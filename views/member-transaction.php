<?php 
$t = explode("/",$_SERVER['REQUEST_URI']);
$k = $t[2];
$mem = $help->get_member($k);
$k=$mem["g_id"];
// echo $k;
$weeks = $help->query("select * from weeks where g_id=:id",[":id"=>$k]);
$ledgers = $help->query("select * from trans_types");
$code = $help->get_last_id("t_id", "trans_action")+1;
$code = "TRS".time()."CMMF";
?>

<h4 class="center-align">
    Make Transaction
</h4>
<div class="row">
	<form id="addTransaction">
		<input type="hidden" name="" id="m_id" value="<?php echo $t[2]; ?>">
        <div class="input-field col s12 browser-default">
        <select  id="w_id" name="w_id" >
          <option value="" selected>Select Week</option>
            <?php
                foreach($weeks->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['w_id'].">".$row['w_code']."</option>";
                }
            ?>
          </select>
          <label for="t_code">Week</label>
        </div>
        <div class="input-field col s12">
          <select  id="trans_type" name="trans_type" onchange="ledger_change(this.value)">
          <option value="" selected>Select Ledger Type</option>
            <?php
                foreach($ledgers->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['ty_id'].">".$row['ty_name']."</option>";
                }
            ?>
          </select>
          <label for="name">Transaction Type</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="number"  value="" id="t_amount" data-length="7" required>
          <label for="t_amount">Amount</label>
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
            "code":"<?php echo $code; ?>",
            "trans_type":$("#trans_type").val(),
            "comment":$("#t_desc").val(),
            "amount":$("#t_amount").val(),
            "week":$("#w_id").val()
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

const ledger_change = (i) =>{
    let mem = `<?php echo $t[2]; ?>`
    let week = $("#w_id").val()

    console.log(`Member: ${mem}\nWeek: ${week}\nLedger: ${i}`)
    $.ajax({
        type: "get",
        url: `${base_url}/api/groupAPI.php?mem=${mem}&week=${week}&ledger=${i}`,
        headers,
        dataType: "json",
        success: function (response) {
            // console.log(response)            
            $("#t_amount").val(response.payments)          
            
        }
    });
}
</script>