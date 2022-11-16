<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
$members = $help->query("select * from group_member where g_id=:grp", [':grp'=>$rt[2]]);
$ledgers = $help->query("select * from trans_types");
$weeks = $help->query("select * from weeks where g_id=:go order by w_id desc", [":go"=>$rt[2]]);
// $code = $help->get_last_id("t_id", "trans_action")+1;
$code = "TRS".time()."CMMF";

?>
<div class="row">
<h4 class="center-align">Make Transaction To Group</h4>
	<form class="col s12"id="addTransaction">
    <div class=" col s12">
        <div class="input-field col s4">
          <select  id="w_id" name="m_id" >
            <option value="" selected>Select Week</option>
            <?php
                foreach($weeks->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['w_id'].">".$row['w_code']."</option>";
                }
            ?>
          </select>
          <label for="name">Week Details</label>
        </div>
		
        <div class="input-field col s8">
          <select  id="trans_type" name="trans_type" onchange="change_ledge(this.value)">
          <option value="" selected>Select Ledger Type</option>
            <?php
                foreach($ledgers->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['ty_id'].">".$row['ty_name']."</option>";
                }
            ?>
          </select>
          <label for="name">Ledger Type</label>
        </div>
    </div>
        <table>
            <tbody id="membs">
                
            </tbody>
        </table>
        
       
        <input type="hidden" name="" id="t_code" value="<?php echo $code; ?>">
        
        <div class="input-field col s12 browser-default">
            <textarea  name="" id="t_desc" class="materialize-textarea" cols="30" rows="5"></textarea>
            <label for="t_desc">Comment</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center green" type="submit" name="action">Add Transaction
    <i class="material-icons right">send</i>
  </button>
  </div>
	</form>
</div>

<script>
    page_title('New Transaction');
    let i = "<?php echo $i; ?>"
    let y = "<?php echo $rt[2]; ?>"
    
    let tym = [];
    let msgg = true;
        
    
    $("#addTransaction").submit(e=>{
        e.preventDefault();
        console.log(tym)
        
        
        for(let x of tym){ 
            $.ajax({
                type: "post",
                url: `${base_url}/api/transactionAPI.php`,
                data: JSON.stringify({
                    "week":$("#w_id").val(),
                    "member":x.m_id,
                    "code":$("#t_code").val(),
                    "trans_type":$("#trans_type").val(),
                    "comment":$("#t_desc").val(),
                    "amount":  Number(x.t_amount) 
                }),
                headers:headers,
                dataType: "json",
                success: function (response) {
                    if(response.status){
                        msgg = true                    
                    }else{
                        msgg = false
                    }
                }
                
            });
        }
        if(msgg){
            toast("TRXN <?php echo $code; ?> was successful...", xtime)
            setTimeout(() => {
                    window.location  = `/groups/${y}/make-transactions`
            }, xtime);
        }else{
            toast("TRXN <?php echo $code; ?> was not successful...", xtime)
        }
});




const change_ledge = (i) =>{
    let grp = `<?php echo $rt[2]; ?>`
    let week = $("#w_id").val()

    // console.log(`Group: ${grp}\nWeek: ${week}\nLedger: ${i}`)
    $.ajax({
        type: "get",
        url: `${base_url}/api/groupAPI.php?payment&grp=${grp}&week=${week}&ledger=${i}`,
        headers,
        dataType: "json",
        success: function (response) {
            console.log(response)
            let up = ""
            for(let r of response.payments){
                up += `<tr>
                    <td>${r.m_code}</td>
                    <td><div class="input-field col s12">
                    <input type="text" oninput="get_mee(this.value, 'id${r.m_id}')" id="amount${r.m_id}" value="${r.t_amount}">
                    
                    <input type="hidden" id="id${r.m_id}" value="${r.m_id}"></td>
                </tr>`;
                tym.push({m_id:r.m_id, t_amount:r.t_amount, id:`id${r.m_id}`, m_id:r.m_id})
            }
            $("#membs").html(up);
        }
    });
}

const get_mee = (x, y) =>{
    for (var i in tym) {
     if (tym[i].id == y) {
        tym[i].t_amount = x;
        break; //Stop this loop, we found it!
     }
   }
}


</script>