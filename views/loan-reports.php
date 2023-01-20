
<div class="row dont-print m-3">
    <!-- <div class="col-4">
        <label for="">Group</label>
        <select class="form-select" id="g_id" onchange="g_change(this.value)"></select>
    </div> -->
    <div class="col-6">
        <label for="">Group</label>
        <select class="form-select" id="ls_id" onchange="s_change(this.value)">
            <option value="">---All---</option>
            <option value="1">Pending</option>
            <option value="2">Approved</option>
            <option value="3">Cancelled</option>
            <option value="4">Settled</option>
            <option value="5">Waiting</option>
        </select>
    </div>
    <div class="col-6">
        <label for="" class="col-form-label">&nbsp;</label>
        <br>
        <button class="btn btn-outline-primary rounded-pill" onclick="print_now('CMMF Loan Report');">Print <i class="bi bi_printer"></i></button>
    </div>
</div>

<div class="row" id="rep_id">

</div>
<script>
    page_title("Loan Reports")
    
    let head = ["#", "MID", "LID", "Amount", "Rate", "To Pay", "Balance", "Expiry Date", `<div class="dont-print"></div>`]
    const loans = () =>{
        let body = []
        let m = localStorage.getItem("lst")
        let k = m==''?'':`&&status=${m}`;
        $.ajax({
        type: "get",
        url: `${base_url}/api/reportsAPI.php?loans${k}`,
        headers,
        dataType: "json",
        success: function (response) {
            console.log(response)
            let ki = 1
            let tt_amount = tt_balance = tt_to_pay=0
            for(let r of response.reports){
                body.push([ki, r.member, r.lo_code,nm.format(r.lo_amount), r.lo_rate+"%", nm.format(r.to_pay), isNaN(r.balance)?"T.B.D":nm.format(r.balance),r.lo_expiry, `<a href="/loans/${r.lo_code}" class="btn btn-outline-success dont-print btn-sm">View</a>`])
                ki++
                if(!isNaN(Number(r.balance))){
                    tt_balance += Number(r.balance)
                }else{}
                tt_amount += Number(r.lo_amount)
                tt_to_pay  += r.to_pay
            }
            body.push(["","Total", "", nm.format(tt_amount), "", nm.format(tt_to_pay), tt_balance<=0?"T.B.D":nm.format(tt_balance), "", `<div class="dont-print"></div>`])
            Table({div:"rep_id", body, head})
        }
        });
    }
    
    loans()
    const g_change = (x) =>{
        localStorage.setItem("lgrp", x)
    }

    const s_change = (x) =>{
        localStorage.setItem("lst", x)
        loans()
    }
    $(document).ready(()=>{
        $.ajax({
            type: "get",
            url: `${base_url}/api/groupAPI.php`,
            headers,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                // let options = []
                let mg = `<option>Select Group</option>`
                for(let n of response.group){
                    mg += `<option value="${n.g_id}">${n.g_code}</option>`
                }
                $("#g_id").html(mg)
                
            }
        });
        $("#ls_id").val(localStorage.getItem("lst"))
        $("#grp").on("change", e=>{
            console.log($("#grp").val())
        })
    })



</script>