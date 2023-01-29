<!-- <h4 class="center-align">Loans</h4> -->
<div class="mb-3 mt-3">
  <input type="text" id="sln" placeholder="Search Loans by loan number, member code, status..."class="form-control form-control-sm">
</div>
  <div class="row" id="loanList">
  <!-- <div class="row mb-3" id="add-div" style="float:right;"></div> -->
  
</div>
<!-- <div id="loan-div"></div> -->
  
<!-- <div class="fixed-action-btn">
  <a class="btn-floating btn-large green" href="/add-loan">
    <i class="large material-icons">add</i>
  </a>
</div> -->

<script>

  const options_function = (x, i) =>{
    let m = ''
    if(x == 1){
      m = `<a href="/loans/${i}/add-guaranter" class="btn btn-sm btn-primary rounded-pill press-loan-officer">Add Guaranter</a>`
    }else if(x==2){
      m = `<a href="/loans/${i}/loan-payment" class="btn btn-sm btn-warning rounded-pill press-loan-officer">Make Payment</a>`
    }else if(x==3){
      m = `<a href="/loans/${i}/loan-payment" class="btn btn-sm btn-warning rounded-pill press-loan-officer">Make Payment</a>`
    }else if(x==5){
      m = `<a href="/loans/${i}" class="btn btn-sm btn-dark rounded-pill press-loan-officer">Waiting Approval</a>`
    }else{
      m = `<button class="btn btn-sm btn-block btn-outline-success rounded-pill press-loan-officer">  Loan Settled <i class=""></li></button>`
    }
    return m;
  }
  page_title('Loans');
    $.ajax({
        type: "get",
        url: `${base_url}/api/loanAPI.php`,
        headers:headers,
        dataType: "json",
        success: function (response) {
            // console.log(response);
            try{
              if(response.loans.length){
              let body = [];
              for(let x of response.loans){
                body.push([x.lo_code,x.member ,nm.format(x.lo_amount),x.lo_rate, nm.format((1+x.lo_rate/100)*x.lo_amount),x.balance, loan_status(x.ls_id), x.lo_expiry,options_function(x.ls_id, x.lo_code),`<button class="btn btn-success btn-sm rounded-pill" onclick="go_to_page(['loans/${x.lo_code}'])">More  <i class='bi bi-eye'></></button>` ])
              }
            
            
            Table({
                div:"loanList", 
                head:["Loan Number", "Member", "Amount", "Rate (%)", "Amount Payable", "Balance","Status", "Expiry Date", `<a href="add-loan" class="btn btn-outline-primary btn-sm loan-officer">Add Loan <i class="bi bi-plus-lg"></i></a>`,`<button class="btn btn-outline-primary rounded-pill btn-sm" onclick="print_now();">Print <i class="bi bi-printer"></button>`],
                body
              })
      }else{
        let op = confirm("No loans yet, add first loan")
        if(op){
          window.location  = "/add-loan"
        }else{
          window.history.go(-1)
        }
      }
    }catch(TypeError){
      logout();
    }
    }
    
    });

    const go_to = (i) =>{
      window.location =`/loans/${i}`
    }

    Anchor({div:"add-div", href:"/add-loan", text:"Add Loan", btn:"primary" })

    $(document).ready(()=>{
      document.querySelector("#sln").addEventListener("keyup", filterTableLoan, false)
    })
    
</script>

