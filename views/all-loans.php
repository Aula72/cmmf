<h4 class="center-align">Loans</h4>
<div class="row" id="loanList">

</div>
  
<div class="fixed-action-btn">
  <a class="btn-floating btn-large green" href="/add-loan">
    <i class="large material-icons">add</i>
  </a>
</div>

<script>
  page_title('Loans');
    $.ajax({
        type: "get",
        url: `${base_url}/api/loanAPI.php`,
        headers:headers,
        dataType: "json",
        success: function (response) {
            console.log(response);
            for(let x of response.loans){
              $("#loanList").append(`<div style="border: 2px; border-color:green;" class="s12 col" onclick="go_to('${x.lo_code}')">
    <h5>Loan:  ${x.lo_code}</h5>
    <p>Amount: ${x.lo_amount}<span style="float:right">Expires On: ${x.lo_expiry}</span></p>
    <span>Rate: ${x.lo_rate}%</span>
  </div>`)
            }
        }
    });

    const go_to = (i) =>{
      window.location =`/loans/${i}`
    }
</script>