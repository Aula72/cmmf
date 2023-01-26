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

<!-- <h4 class="center-align">
    Make Transaction
</h4> -->
<div class="row">
	<form id="addTransaction">
		<input type="hidden" name="" id="m_id" value="<?php echo $t[2]; ?>">
        <div class="row mb-3">
            <label class="col-sm-2 col-form-label">Select Week</label>
                  <div class="col-sm-10">
        <select  id="w_id" class="form-select rounded-pill" name="w_id" >
          <option value="" selected>Select Week</option>
            <?php
                foreach($weeks->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['w_id'].">".$row['w_code']."</option>";
                }
            ?>
          </select>
            </div>
        </div>
        <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Transaction Type</label>
                  <div class="col-sm-10">
          <select class="form-select rounded-pill"  id="trans_type" name="trans_type" onchange="ledger_change(this.value)">
          <option value="" selected>Select Ledger Type</option>
            <?php
                foreach($ledgers->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['ty_id'].">".$row['ty_name']."</option>";
                }
            ?>
          </select>
            </div>
        </div>
        <div id="amnt-div"></div>
        <div id="com-div"></div>
        <div id="btn-div"></div>
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
            try{
                if(response.status){
                    toast(response.message)
                    setTimeout(() => {
                        window.location  = `/members/${$("#m_id").val()}`
                    }, xtime);
                }else{
                    toast(response.error, 'danger')
                }
            }catch(TypeError){
                logout();
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

Input({div:'amnt-div', id:"t_amount", value:"", type:"number", label:"Amount"})
TextArea({div:"com-div", id:"t_desc", label:"Comment", placeholder:""})
Button({div:"btn-div", label:"Submit", type:"submit", btn:"success", icon:"send"})

allow_url([2])
</script>