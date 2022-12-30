<?php 
$t = explode('/',$_SERVER['REQUEST_URI']);

$ledgers = $help->query("select * from trans_types");

$weeks = $help->query("select * from weeks");

$groups = $help->query("select * from grouping");
?>
<div class="row">
    <!-- <h4 class="center-align"></h4> -->
    <!-- <div class="row mb-3"> -->
        <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Ledger Type</label>
                  <div class="col-sm-10">
            <select class="form-select" id="trans_type" name="trans_type" value="<?php echo $t[2]; ?>" onchange="on_change(this.value)" >
            <option value="" selected>Select Ledger Type</option>
                <?php
                    foreach($ledgers->fetchAll(PDO::FETCH_ASSOC) as $row){
                        $b = $t[2]==$row['ty_id']?"selected":"";
                        echo "<option value='".$row['ty_id']."' {$b}>".$row['ty_name']."</option>";
                    }
                ?>
            </select>
                </div>
        </div>
    <!-- </div> -->
    <!-- <div class="col s12"> -->
    <div class="row mb-3" >
    <label class="col-sm-2 col-form-label">Group</label>
                  <div class="col-sm-10">
            <select class="form-select" id="grp" name="grp" value="" onchange="on_grp_change(this.value)">
            <option value="" selected>Select Group</option>
                <?php
                    foreach($groups->fetchAll(PDO::FETCH_ASSOC) as $row){
                        echo "<option value=".$row['g_id'].">".$row['g_code']."</option>";
                    }
                ?>
            </select>
            </div>
        </div>
        <div class="row mb-3" id="xweeks">
        <label class="col-sm-2 col-form-label">Week</label>
                  <div class="col-sm-10">
            <select class="form-select" id="week" name="week"  onchange="on_week_change(this.value, <?php echo $t[2]; ?>)">
                <option value="" selected>Select Week</option>
                 
            </select>
                </div>
        </div>
    </div>
</div>

<table id="ledger" class="table">
    <thead>
        <tr>
            <th>MID</th>
            <th>Week</th>
            <th>Amount</th>
        </tr>
    </thead> 
    <tbody id="l_list"></tbody>               
</table>
<!-- <div class="fixed-action-btn" id="btns"> -->
  <button class="btn btn-primary" onclick="print_now()">
                    Print 
    <i class="bi bi-printer"></i>
    </button>
<!-- </div> -->

<script>
    page_title("<?php echo $help->get_ledger_name($t[2]); ?>")
    const on_change = (id)=>{
        window.location = `/ledgers/${id}`
    }
    const on_week_change = (id, som)=>{
        // console.log(som)
        // window.location = `/ledgers/${id}`
        load_sums(som, id)
    }
    const on_grp_change =(i)=>{
        let xhr = new XMLHttpRequest()
        xhr.open('GET', `${base_url}/api/weekAPI.php?grp=${i}`,true)
        xhr.onload = function(){
            // console.log(this.responseText)
            try{
                let p = JSON.parse(this.responseText)
                if(p.weeks.length){
                    let r = `<option>Select Week</option>`
                    for(let j of p.weeks){
                        r += `<option value="${j.w_id}">${j.w_code}</option>`
                    }
                    $("#week").html(r)
                    // $('select').material_select();
                }else{
                    if(confirm("No weeks in this group add first one")){

                    }else{

                    }
                }
            }catch(TypeError){
                logout();
            }
        }
        xhr.setRequestHeader("auth", token);
        xhr.setRequestHeader("content-type", "application/json")
        xhr.send();
    }
    const load_sums = (i, x) =>{
        // console.log(`This is week....`)
        $.ajax({
            type: "get",
            url: `${base_url}/api/ledgerAPI.php?id=${i}&week=${x}`,
            headers,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                try{
                    let trans = response.trans;
                    let trans_sum = response.trans_sum 
                    let po = ""
                    if(trans.length){
                        for(let b of trans){
                            po += `<tr onclick="trans_details(${b.t_id})"><td>${b.member}</td><td>${b.week}</td><td>${nm.format(b.t_amount)}/=</td></tr>`
                        }
                        po += `<tr><td>Total</td><td></td><td>${trans_sum}</td></tr>`;
                        $("#l_list").html(po);
                    }else{
                        $("#ledger").html(`<div class="center-align text-red" >No transactions in this category yet!</div>`);
                    }
                }catch(TypeError){
                    logout();
                }
            }
        });
    }


    const trans_details =(p)=>{
        // alert(p)
        $.ajax({
            type: "get",
            url: `${base_url}/api/ledgerAPI.php?id=${p}`,
            headers,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                let t = response;
                alert(`TRXN:     ${t.t_code}\nAmount:    ${nm.format(t.t_amount)}\nMember:   ${t.member}\nWeek:     ${t.week}\nComment:    ${t.t_desc}\nTime:     ${t.created_at}`)
            }
        });
    }
    
    
    // $("#week").val("")?$("#ledger").hide():$("#ledger").show();
    // $("#week").val("")?$("#btns").hide():$("#btns").show();
</script>