<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
// $members = $help->query("select * from group_member where g_id=:grp", [':grp'=>$rt[2]]);
$ledgers = $help->query("select * from trans_types");
$weeks = $help->query("select * from weeks where w_id=:go order by w_id desc", [":go"=>$rt[2]]);
// $code = $help->get_last_id("t_id", "trans_action")+1;
$weeks = $weeks->fetch(PDO::FETCH_ASSOC);
$code = "TRS".time()."CMMF";

?>
<div class="row">
<!-- <h4 class="center-align">Make Transaction To Group ko</h4> -->
	<form class="col s12" id="addTransaction23">
        <input type="hidden" name="" id="w_id" value="<?php echo $rt[2];?>">
    <!-- <div class=" row mb-3">
    <label class="col-sm-2 col-form-label">Week Details</label>
                  <div class="col-sm-10">
          <select  id="w_id" name="m_id" class="form-select rounded-pill">
            <option value="" selected>Select Week</option>
            <?php
                // foreach($weeks->fetchAll(PDO::FETCH_ASSOC) as $row){
                //     echo "<option value=".$row['w_id'].">".$row['w_code']."</option>";
                // }
            ?>
          </select>
            </div>
        </div> -->
		
        <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Ledger Type</label>
                  <div class="col-sm-10">
          <select class="form-select rounded-pill" id="trans_type" name="trans_type" onchange="change_ledge(this.value)">
          <option value="" selected>Select Ledger Type</option>
            <?php
                foreach($ledgers->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['ty_id'].">".$row['ty_name']."</option>";
                }
            ?>
          </select>
            </div>
        </div>
    </div>
        <table class="table">
            <tbody id="membs" >
                
            </tbody>
        </table>
        
       
        <input type="hidden" name="" id="t_code" value="<?php echo $code; ?>">
        
        
        <div id="com-div"></div>
        <!-- <div id="btn-div"></div> -->
        <button type="submit" class="btn btn-outline-success rounded-pill">Save<i class="bi bi-send"></i></button>
        
	</form>
</div>

<script>
    page_title('New Transaction');
    let i = "<?php echo $i; ?>"
    let y = "<?php echo $rt[2]; ?>"

    
    let tym = [];
    let msgg = true;
        
    
const change_ledge = (i) =>{
    let grp = `<?php echo $weeks["g_id"]; ?>`
    let week = $("#w_id").val()

    // console.log(`Group: ${grp}\nWeek: ${week}\nLedger: ${i}`)
    $.ajax({
        type: "get",
        url: `${base_url}/api/groupAPI.php?payment=undefined&grp=${grp}&week=${week}&ledger=${i}`,
        headers,
        dataType: "json",
        success: function (response) {
            // console.log(response)
            let up = ""
            let iom = ""
            try{
                for(var r of response.payments){
                    // console.log(r)
                    up += `<tr>
                        <td>${r.m_code}</td>
                        <td><div class="">
                        <input type="text" oninput="get_mee(this.value, 'id${r.m_id}')" id="amount${r.m_id}" value="${r.t_amount}" class="form-control rounded-pill">
                        
                        <input type="hidden" id="id${r.m_id}" value="${r.m_id}"></td>
                    </tr>`;
                    tym.push({m_id:r.m_id, t_amount:r.t_amount, id:`id${r.m_id}`, m_code:r.m_code})
                }
                // console.log(tym)
                $("#membs").html(up);
            }catch(TypeError){
                logout();
            }
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
   console.log(tym)
}
    $("#addTransaction23").submit(e=>{
        e.preventDefault();

        
        
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
            // alert("simon")
            toast("TRXN <?php echo $code; ?> was successful...", xtime)
            setTimeout(() => {
                    window.location  = `/weeks/${y}/week-transactions`
            }, xtime);
        }else{
            toast("TRXN <?php echo $code; ?> was not successful...", xtime)
        }
});



TextArea({div:"com-div", id:"t_desc", label:"Comment", placeholder:""})
Button({div:"btn-div", label:"Add Transaction", type:"submit", btn:"success", icon:"send",})


allow_url([2])
</script>