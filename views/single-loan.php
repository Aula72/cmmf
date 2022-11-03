<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
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
<h4 class="center-align">Recent Payments</h4>
<h4 class="center-align">Guaranter(s)</h4>
<table>
    <tbody id="gList">

    </tbody>
</table>
<h4 class="center-align">Fines</h4>

<div class="fixed-action-btn">
<a class="btn-floating btn-large green">
    <i class="large material-icons">edit</i>
  </a>
  <ul>
    
    <li><a class="btn-floating red"><i class="material-icons">T</i></a></li>
    <li><a class="btn-floating yellow darken-1"><i class="material-icons">L</i></a></li>
    <li><a class="btn-floating purple" href="/loans/<?php echo $rt[2]?>/add-guaranter"><i class="material-icons">G</i></a></li>
    <!-- <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li> -->
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
            console.log(response)
            page_title(response.loan.lo_code);
            let p = response.loan;
            let q= response.member;
            $("#lname").text(p.lo_code)
            $("#name").text(response.loan.lo_code)
            $("#owner").text(`${q.m_lname} ${q.m_fname}`)
            $('#phone').text(`${q.m_phone}`);
            $('#status').text(p.ls_id)
            $('#amount').text(p.lo_amount)
            $("#duedate").text(p.lo_expiry)
            $("#phone").text(q.m_phone);
            $("#sdate").text(p.created_at.substring(0, 10))
            for(let c of response.guaranters){
                $("#gList").append(`<tr><td>${c.name}</td><td>${c.amount}</td></tr>`)
            }
        }
    });
</script>