<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
$id = $help->get_loan_id($rt[2]);
$own = $help->get_member($id["m_id"]);

$bal = $id["lo_amount"] - $help->guarant_balance($id["lo_id"]);
?>
<h3 id="name" class="center-align"></h3>
<table>
    <tr>
        <td>Loan Number</td>
        <td id="lname"></td>
    </tr>
    <tr>
        <td>Own Name</td>
        <td id="owner"></td>
    </tr>
    <tr>
        <td>Status</td>
        <td id="status"></td>
    </tr>
    <tr>
        <td>Owner's Contact</td>
        <td id="phone"></td>
    </tr>
    <tr>
        <td>Start Date</td>
        <td id="sdate"></td>
    </tr>
    <tr>
        <td>Amount</td>
        <td id="amount"></td>
    </tr>
    <tr>
        <td>Due Amount</td>
        <td id="due"></td>
    </tr>
    <tr>
        <td>Due Date</td>
        <td id="duedate"></td>
    </tr>
    
</table>
<!-- <h4 class="center-align">Owner's Details</h4> -->
<div id="payments">
<h4 class="center-align">Recent Payments</h4>
    <table>
        <tbody id="pList">

        </tbody>
    </table>
</div>

<div id="guaranters">
    <h4 class="center-align">Guaranter(s)</h4>
    <table>
        <tbody id="gList">

        </tbody>
    </table>
</div>
 
<div id="fines">
    <h4 class="center-align">Fines</h4>
</div>


<div class="fixed-action-btn">
<a class="btn-floating btn-large green">
    <i class="large material-icons">edit</i>
  </a>
  <ul>
    
    <li id="add-payment"><a class="btn-floating red" href="/loans/<?php echo $rt[2]; ?>/loan-payment"><i class="material-icons">euro</i></a></li>
    <!-- <li><a class="btn-floating yellow darken-1"><i class="material-icons">L</i></a></li> -->
    <li id="add-guaranter"><a class="btn-floating purple" href="/loans/<?php echo $rt[2]?>/add-guaranter" ><i class="material-icons">G</i></a></li>
    <li><a class="btn-floating blue" onclick="print_now()"><i class="material-icons">print</i></a></li>
  </ul>
</div>
<script>
    page_title('Loading....');
    let id = "<?php echo $rt[2]; ?>";
    
    
    
    $.ajax({
        type: "get",
        url: `${base_url}/api/loanAPI.php?id=${id}`,
        headers,
        dataType: "json",
        success: function (response) {
            // console.log(response)
            page_title(response.loan.lo_code);
            let p = response.loan;
            let q= response.member;
            $("#lname").text(p.lo_code)
            $("#name").text(response.loan.lo_code)
            $("#owner").text(`${q.m_lname} ${q.m_fname}`)
            $('#phone').text(`${q.m_phone}`);
            $('#status').text(response.status_name)
            $('#amount').text(`${nm.format(p.lo_amount)} /=`)
            $("#duedate").text(p.lo_expiry)
            $("#phone").text(q.m_phone);
            $("#due").text(`${nm.format((1+p.lo_rate/100)*p.lo_amount)} /=`)
            $("#sdate").text(p.created_at.substring(0, 10))
            response.fines.length?$("#fines").show():$("#fines").hide();
            response.guaranters.length?$("#guaranters").show():$("#guaranters").hide();
            response.payments.length?$("#payments").show():$("#payments").hide();
            if(response.status==1){
                $("#add-guaranter").css({display:"inline-block"});
            }else{
                $("#add-guaranter").css({display:"none"});
            }

            response.status==2?$("#add-payment").show():$("#add-payment").hide();
            for(let c of response.guaranters){
                $("#gList").append(`<tr><td>${c.name}</td><td>${c.amount}</td></tr>`)
            }
            for(let c of response.payments){
                $("#pList").append(`<tr onclick="trans_details(${c.p_id})"><td>${c.trans_id}</td><td>${nm.format(c.amount)}/=</td><td>${c.created_at}</td></tr>`)
            }
        }
    });
    // alert(`Name: Aula Simon\nAge: 35\nDOB 23-12-1990\nMartial Status: M`)
    // alert(`<table></table>`)
    const trans_details =(id) =>{
        $.ajax({
            type: "get",
            url: `${base_url}/api/loanPaymentAPI.php?id=${id}`,
            headers,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                let res = response.trans;
                alert(`TRXN. ID:   ${res.trans_id}\nAmount:     ${nm.format(res.amount)}/=\nDate:       ${res.created_at}\nComments:   ${res.p_comment}`)
            }
        });
    }
</script>

