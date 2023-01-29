<div class="row dont-print">
    <div class="col-lg-4 col-sm-12 col-md-4">
        <label for="" class="col-form-label">Group</label>
        <select class="form-select col-lg-4" name="" id="g_id" onchange="change_grp(this.value)"></select>
    </div>
    <div class="col-lg-4 col-sm-12 col-md-4"></div>
    <div class="col-lg-4 col-sm-12 col-md-4">
        <label for="" class="col-form-label">&nbsp;</label>
        <br>
        <button class="btn btn-outline-primary rounded-pill" onclick="print_now('CMMF Transaction Report');">Print <i class="bi bi_printer"></i></button>
    </div>
</div>

<div class="row mt-3" id="reports">
    
</div>
<script>
    page_title("Transaction Reports")
    $("g_id").val(localStorage.getItem("xgr"))
    const change_grp =(x)=>{
        console.log(x)
        localStorage.setItem("xgr", x)
        let head = ["MID", "Saving", "Social Fund", "Fines","Edn In", "Edn Out", "Subscription","Loan Out", "Loan Payment", "Loan Forms", "Membership", 'Net Worth',`<div class="dont-print"></div>`]
        var body = []
        let t_saving = t_social = t_fine = t_edin = t_edout = t_sub = t_mem = t_lout = t_repay = t_lform= 0;
        $.ajax({
            type: "get",
            url: `${base_url}/api/reportsAPI.php?group=${localStorage.getItem("xgr")}`,
            headers,
            dataType: "json",
            success: function (response) {
                console.log(response)
                if(response.reports.length){
                    for(let r of response.reports){
                        body.push([r.m_code, nm.format(r.saving), nm.format(r.social_fund), nm.format(r.fine), nm.format(r.education_in), nm.format(r.education_out), nm.format(r.subscription), nm.format(r.loan_out), nm.format(r.repayment), nm.format(r.loan_form),nm.format(r.membership), nm.format(r.net_worth), `<a href="/members/${r.m_id}" class="dont-print btn btn-sm btn-outline-success">View</a>`])
                        t_saving += Number(r.saving)
                        t_fine += Number(r.fine)
                        t_social += Number(r.social_fund)
                        t_edin += Number(r.education_in)
                        t_edout += Number(r.education_out)
                        t_sub += Number(r.subscription)
                        t_mem += Number(r.membership)
                        t_lout += Number(r.loan_out);
                        t_repay += Number(r.repayment)
                    }
                    body.push(["Total", nm.format(t_saving), nm.format(t_social), nm.format(t_fine), nm.format(t_edin), nm.format(t_edout), nm.format(t_sub), nm.format(t_lout), nm.format(t_repay), nm.format(t_lform), nm.format(t_mem), response.total_net])
                    // console.log(body)

                    Table({div:"reports",head, body})
                }else{
                    $("#reports").html(`<p class="text-center">No records</p>`);
                }
            }
            
        });
        
        
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
        $("#grp").on("change", e=>{
            console.log($("#grp").val())
        })
    })

    
</script>