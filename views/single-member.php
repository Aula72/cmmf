<?php 
$t = explode("/",$_SERVER['REQUEST_URI']);
?>
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
  <tr>
    <td>Account Balance</td>
    <td id="acc"></td>
  </tr>
  
</table>

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
      <h5 class="center-align">Next of Kin</h5>
      <table>
        <thead>
          <tr>
              <th>Name</th>
              <th>Item Name</th>
              <th>Item Price</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>Alvin</td>
            <td>Eclair</td>
            <td>$0.87</td>
          </tr>
          <tr>
            <td>Alan</td>
            <td>Jellybean</td>
            <td>$3.76</td>
          </tr>
          <tr>
            <td>Jonathan</td>
            <td>Lollipop</td>
            <td>$7.00</td>
          </tr>
        </tbody>
      </table>
      <div class="fixed-action-btn">
  <a class="btn-floating btn-large green">
    <i class="large material-icons">edit</i>
  </a>
  <ul>
    
    <li><a class="btn-floating red" href="/members/<?php echo $t[2]; ?>/add-transaction"><i class="material-icons">T</i></a></li>
    <li><a class="btn-floating yellow darken-1"><i class="material-icons">L</i></a></li>
    <li><a class="btn-floating purple" href="/members/<?php echo $t[2]; ?>/add-next-of-kin"><i class="material-icons">K</i></a></li>
    <!-- <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li> -->
  </ul>
<script>
  page_title('loading...');
  let m_id = localStorage.getItem("m_id");
  $.ajax({
    type: "get",
    url: `${base_url}/api/groupMemberAPI.php?id=${m_id}`,
    headers:headers,
    dataType: "json",
    success: function (response) {
      console.log(response)
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

      let trans = response.transaction;
      for(let c of trans){
        $("#tList").append(`<tr><td>${c.t_code}</td><td>${c.t_amount}</td><td>${c.created_at.substring(0, 10)}</td></tr>`)
      }
      let loan = response.loans;
      for(let b of loan){
        $("#lLoans").append(`<tr onclick="go_to_loan(${b.lo_id})"><td>${b.lo_code}</td><td>${b.lo_amount}</td><td>${b.created_at.substring(0, 10)}</td></tr>`);
      }
    }
  });

  const go_to_loan = (i) =>{
    window.location = `/loans/${i}`
  }
</script>
            

