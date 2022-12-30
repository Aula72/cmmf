<?php 
$mem  = $help->query("select * from grouping");

$code = "LN".$code.date("mY");
?>
<style type="text/css" media="print">
  @page { 
    size: landscape; 
    }
</style>
<div class="row" id="trow">
        <h4 class="center-align" id="gcode"></h4>
        <div class="mb-3">
        <label for="name">Group Details</label>
          <select class="form-select rounded-pill" id="g_id" name="m_id" >
            <option value="" selected>Select Group</option>
            <?php
                foreach($mem->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['g_id'].">".$row['g_code']."</option>";
                }
            ?>
          </select>
          
        </div>
        <div class="input-field center-align col s2">
            <button class="btn btn-small green" onclick="print_report()">Print</button>
        </div>
</div>

<div class="row" id="groups" style="display:none;">
    <table class="table">
        <thead>
            <tr>
                <th>Mem No.</th>
                <th>Savings</th>
                <th>Social Fund</th>
                <th>Fine</th>
                <th>Edn. In</th>
                <th>Edn. Out</th>
                <th>Subsciption</th>
                <th>Repayment</th>
                <th>Loan Out</th>
                <th>Loan Charges</th>
                <th>Loan Forms</th>
                <th>Membership</th>
                
            </tr>
        </thead>
        <tbody id="reportL">
            
        </tbody>
    </table>
</div>

<div class="fixed-action-btn">
  <a class="btn-floating btn-large green" onclick="print_report()">
    <i class="large material-icons">print</i>
  </a>
</div>

<script>
    page_title("Reports");
    $("#g_id").on("change", e=>{
        e.preventDefault()
        console.log($("#g_id").val())
        let j = $("#g_id").val();
        $.ajax({
            type: "get",
            url: `${base_url}/api/reportsAPI.php?id=${j}`,
            headers,
            data: "data",
            dataType: "json",
            success: function (response) {
                console.log(response)
                let m = ""
                for(let u of response.reports){
                    m +=`
                    <tr>
                        <td>${u.m_code}</td>
                        <td>${nm.format(u.savings)}</td>
                        <td>${nm.format(u.sfund)}</td>
                        <td>${nm.format(u.fine)}</td>
                        <td>${nm.format(u.edu_in)}</td>
                        <td>${nm.format(u.edu_out)}</td>
                        <td>${nm.format(u.subscription)}</td>
                        <td>${nm.format(u.repayment)}</td>
                        <td>${nm.format(u.loan_out)}</td>
                        <td>${nm.format(u.loan_charges)}</td>
                        <td>${nm.format(u.loan_form)}</td>
                        <td>${nm.format(u.membership)}</td>
                        
                    </tr>
                    `
                }
                $("#reportL").html(m);
            }
        });
        $("#groups").show();
    })


    const print_report =()=>{
        $("#trow").hide();
        print_now();
        $("#trow").show();
    }
</script>

<style>
    body{
        overflow-x: hidden;
        overflow-y: hidden;
    }
</style>

