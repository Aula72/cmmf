<?php 
$t = explode("/",$_SERVER['REQUEST_URI']);
?>
<h4 class="center-align">Member Details</h4>
<table>
  <tr>
    <td>Name</td>
    <td id="name"></td>
  </tr>
  <tr>
    <td>Phone Number</td>
    <td id="phone"></td>
  </tr>
  <tr>
    <td>NIN</td>
    <td id="nin"></td>
  </tr>
  <tr>
    <td>Date of Birth</td>
    <td id="dob"></td>
  </tr>
  <tr>
    <td>Gender</td>
    <td id="gender"></td>
  </tr>
  <tr>
    <td>Member Code</td>
    <td id="mcode"></td>
  </tr>
  
  <tr>
    <td>Group Code</td>
    <td id="g_code"></td>
  </tr>
  
</table>
<h5 class="center-align">Accounts</h5>
<table>
  <thead>
    <tr>
      <th>Ledger</th>
      <th>Total Amount</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Savings</td>
      <td id="saving"></td>
    </tr>
    <tr>
      <td>Social Fund</td>
      <td id="social"></td>
    </tr>
    <tr>
      <td>Fine</td>
      <td id="fine"></td>
    </tr>
    <tr>
      <td>Education In</td>
      <td id="edu_in"></td>
    </tr>
    <tr>
      <td>Education Out</td>
      <td id="edu_out"></td>
    </tr>
    <tr>
      <td>Subscription</td>
      <td id="subs"></td>
    </tr>
    <tr>
      <td>Repayment</td>
      <td id="repay"></td>
    </tr>
    <tr>
      <td>Loan Out</td>
      <td id="loan_out"></td>
    </tr>
    <tr>
      <td>Loan Form</td>
      <td id="loan_for"></td>
    </tr>
    <tr>
      <td>Membership</td>
      <td id="membership"></td>
    </tr>
  </tbody>
</table>
<div id="trans">
<h5 class="center-align">Transactions</h5>
      <table>
        <thead>
          <tr>
              <th>Trans ID</th>
              <th>Amount</th>
              <th>Date</th>
          </tr>
        </thead>

        <tbody id="tList">
          
        </tbody>
      </table>
</div>
<div id="loans">

<h5 class="center-align">Loans</h5>
      <table>
        <thead>
          <tr>
              <th>Loan No.</th>
              <th>Amount</th>
              <th>Date</th>
          </tr>
        </thead>

        <tbody id="lLoans">
          
          
        </tbody>
      </table>
</div>
<div id="lnext">
      <h5 class="center-align">Next of Kin</h5>
      <table>
        <thead>
          <tr>
              <th>Name</th>
              <th>Relationship</th>
              <th>Phone</th>
              
          </tr>
        </thead>

        <tbody id="nList">
          
        </tbody>
      </table>
</div>
      <div class="fixed-action-btn">
  <a class="btn-floating btn-large green">
    <i class="large material-icons">more</i>
  </a>
  <ul>
  <li><a class="btn-floating black" href="/members/<?php echo $t[2]; ?>/edit"><i class="material-icons">edit</i></a></li>
    <li><a class="btn-floating red" href="/members/<?php echo $t[2]; ?>/add-transaction"><i class="material-icons">payments</i></a></li>
    <li><a class="btn-floating yellow darken-1"><i class="material-icons">credit_score</i></a></li>
    <li><a class="btn-floating purple" href="/members/<?php echo $t[2]; ?>/add-next-of-kin"><i class="material-icons">diversity_1</i></a></li>
    <li><a class="btn-floating blue" onclick="print_now();"><i class="material-icons">print</i></a></li>
  </ul>
<script>
  page_title('loading...');
  let m_id = "<?php echo $t[2]; ?>";
  $.ajax({
    type: "get",
    url: `${base_url}/api/groupMemberAPI.php?id=${m_id}`,
    headers:headers,
    dataType: "json",
    success: function (response) {
      // console.log(response)
      let x = response.member;
      page_title(`Member: ${x.m_fname} ${x.m_lname}`);
      $("#acc").text(response.balance)
      $("#name").text(`${x.m_fname} ${x.m_lname}`);
      $("#phone").text(`${x.m_phone}`)
      $("#memail").text(`${x.m_mail}`)
      $("#dob").text(x.m_dob)
      $("#nin").text(x.m_nin)
      $("#mcode").text(x.m_code)
      $("#gender").text(x.m_gender=='1'?'Male':'Female')
      $("#g_code").text(response.group_code)
      /*
      {
    "member": {
        "m_id": "2",
        "m_code": "M2",
        "g_id": "2",
        "user_id": "2",
        "m_fname": "G0MFS2FVa6",
        "m_lname": "yAPmwegYcf",
        "m_phone": "387356",
        "m_nin": "Y3Ujp9uEMf",
        "m_gender": "1",
        "m_dob": "2001-10-10",
        "created_at": "2022-11-04 18:03:22",
        "update_at": "2022-11-04 18:03:22"
    },
    "next_of_kin": [],
    "loans": [],
    "ledgers": [],
    "group_code": "G2",
    "balance": "85,300",
    "savings": "0",
    "social_fund": "0",
    "fines": "0",
    "education_in": "0",
    "education_out": "0",
    "subscription": "0",
    "repayment": "0",
    "loan_out": "0",
    "loan_charge": "0",
    "loan_forms": "0",
    "membership": "0",
    
      */
     let k = response;
      $("#saving").text(`${k.savings} /=`)
      $("#loan_for").text(`${k.loan_forms} /=`)
      $("#loan_charge").text(`${k.loan_charge} /=`)
      $("#loan_out").text(`${k.loan_out} /=`)
      $("#membership").text(`${k.membership} /=`)
      $("#repay").text(`${k.repayment} /=`)
      $("#subs").text(`${k.subscription} /=`)
      $("#edu_out").text(`${k.education_out} /=`)
      $("#edu_in").text(`${k.education_in} /=`)
      $("#social").text(`${k.social_fund} /=`)
      $("#fine").text(`${k.fines} /=`)
      let trans = response.transaction;
      for(let c of trans){
        $("#tList").append(`<tr onclick="trans_detail(${c.t_id})"><td>${c.t_code}</td><td>${nm.format(c.t_amount)}</td><td>${c.created_at.substring(0, 10)}</td></tr>`)
      }
      let loan = response.loans;
      for(let b of loan){
        $("#lLoans").append(`<tr onclick="go_to_loan(${b.lo_id})"><td>${b.lo_code}</td><td>${b.lo_amount}</td><td>${b.created_at.substring(0, 10)}</td></tr>`);
      }
      for(let b of k.next_of_kin){
        $("#nList").append(`<tr onclick="next_of_k(${b.n_id})"><td>${b.full_name}</td><td>${b.relation}</td><td>${b.phone}</td></tr>`);
      }
      trans.length?$("#trans").show():$("#trans").hide();
      loan.length?$("#loans").show():$("#loans").hide();
      k.next_of_kin.length?$("#lnext").show():$("#lnext").hide();
    }
  });

  const go_to_loan = (i) =>{
    window.location = `/loans/${i}`
  }

  const next_of_k =(i)=>{
    $.ajax({
      type: "get",
      url: `${base_url}/api/nextOfKinAPI.php?id=${i}`,
      headers,
      dataType: "json",
      success: function (response) {
        console.log(response)
        let n = response.kin;
        alert(`----Next of Kin Details-----\nFull Name:  ${n.full_name}\nDate of Birth:   ${n.dob}\nPhone:   ${n.phone}\nRelationship:  ${n.relation}\nLocation:   ${n.location}`)
      }
    });
  }
  const trans_detail =(id) =>{
        $.ajax({
            type: "get",
            url: `${base_url}/api/transactionAPI.php?id=${id}`,
            headers,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                let res = response.trans;
                alert(`TRXN. ID:   ${res.t_code}\nAmount:     ${nm.format(res.t_amount)}/=\nDate:       ${res.created_at}\nComments:   ${res.t_desc}`)
            }
        });
    }
</script>
            

