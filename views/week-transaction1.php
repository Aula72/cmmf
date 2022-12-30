<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
$week = $help->query("select * from weeks where w_id=:w", [":w"=>$rt[2]]);
$week = $week->fetch(PDO::FETCH_ASSOC);
$members = $help->query("select * from group_member where g_id=:grp", [':grp'=>$week['g_id']]);
$ledgers = $help->query("select * from trans_types");
$weeks = $help->query("select * from weeks where g_id=:go order by w_id desc", [":go"=>$rt[2]]);
// $code = $help->get_last_id("t_id", "trans_action")+1;
$code = "TRS".time()."CMMF";

?>
<div class="row">
<!-- <h4 class="center-align">Make Transaction To Group</h4> -->
	<form class="col s12"id="addTransaction">
    <div class=" col s12">
        
            <input type="hidden" name="" id="w_id" value="<?php echo $rt[2]; ?>">
            <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Ledger Type</label>
                  <div class="col-sm-10">
                    <select id="trans_type" onchange="change_ledge(this.value)" class="form-select rounded-pill" aria-label="Default select example">
                      <option selected>Open this select menu</option>
                      <?php
                            foreach($ledgers->fetchAll(PDO::FETCH_ASSOC) as $row){
                                echo "<option value=".$row['ty_id'].">".$row['ty_name']."</option>";
                            }
                        ?>
                    </select>
                  </div>
                </div>
        
    </div>
        <!-- <table> -->
            <div id="membs">
                
            </div>
            <?php 
            // foreach($members->fetchAll(PDO::FETCH_ASSOC) as $row){
                ?>
                    
            <?php 
            // }
            ?>
        <!-- </table> -->
        
       
        <input type="hidden" name="" id="t_code" value="<?php echo $code; ?>">
        
        <!-- <div class="input-field col s12 browser-default">
            <textarea  name="" id="t_desc" class="materialize-textarea" cols="30" rows="5"></textarea>
            <label for="t_desc">Comment</label>
        </div> -->
        <div class="form-floating mb-3">
            <textarea id="t_desc" class="form-control" placeholder="Leave a comment here" id="floatingTextarea" style="height: 100px;"></textarea>
            <label for="floatingTextarea">Comments</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn btn-success rounded-pill" type="submit" name="action">Add Transaction
    <i class="bi bi-send"></i>
  </button>
  </div>
	</form>
</div>

<script>
    let wc = "<?php echo $week["w_code"];?>"
    page_title(wc+' /Weekly Transaction');
    let i = "<?php echo $i; ?>"
    let y = "<?php echo $rt[2]; ?>"
    // let y = "<?php echo $week['g_id'];?>"
    let tym = [];
    let msgg = true;
        
    
const change_ledge = (i) =>{
    let grp = `<?php echo $week['g_id']; ?>`
    let week = $("#w_id").val()

    console.log(`Group: ${grp}\nWeek: ${week}\nLedger: ${i}`)
    $.ajax({
        type: "get",
        url: `${base_url}/api/groupAPI.php?payment&grp=${grp}&week=${week}&ledger=${i}`,
        headers,
        dataType: "json",
        success: function (response) {
            console.log(response)
            let up = ""
            let iom = ""
            for(var r of response.payments){
                console.log(r)
                up +=  `<div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">${r.m_code}</label>
                  <div class="col-sm-10">
                    <input type="number" oninput="get_mee(this.value, 'id${r.m_id}')" id="amount${r.m_id}" value="${r.t_amount}" type="text" class="form-control rounded-pill">
                    <input type="hidden" id="id${r.m_id}" value="${r.m_id}"></td>
                  </div>
                </div>`
                tym.push({m_id:r.m_id, t_amount:r.t_amount, id:`id${r.m_id}`, m_code:r.m_code})
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
                    window.location  = `/weeks/${y}/week-transactions`
            }, xtime);
        }else{
            toast("TRXN <?php echo $code; ?> was not successful...", xtime)
        }
});






</script>