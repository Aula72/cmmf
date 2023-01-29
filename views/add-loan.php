<?php 
$mem  = $help->query("select * from grouping");
$year  = $help->query("select * from finanial_year order by y_id desc limit 1");
// $m = $mem->fetch(\PDO::FETCH_ASSOC);
$y = $year->fetch(\PDO::FETCH_ASSOC);

$u = $y["name"]>10?$y["name"]:"0".$y["name"];
// $code = "LN".$code.date("mY");
$oi = $help->get_last_id("lo_id","loans")+1;
// $code = $m["g_code"].$u.$io;
$exp = date('Y-m-d', strtotime("+1 year"));
// $g = $help->number_with_zeros(3, 4);
?>
<div class="row">
<!-- <h4 class="center-align">Add New Loan</h4> -->
	<form class="col s12" id="addLoan">
		<div class="col s12">
      
    <div class="row mb-3">
    <label class="col-sm-2 col-form-label" for="member">Group</label>
    <div class="col-sm-10">
    
          <select class="form-select rounded-pill" name="" id="group" onchange="group1(this.value)">
            <option value="" selected>Choose Member</option>
            <?php
                foreach($mem->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['g_id'].">".$row['g_code']."</option>";
                }
            ?>
          </select>
              </div>
        </div>
        
        <!-- <div id="grp-select"></div> -->
        <div class="row mb-3">
        <label class="col-sm-2 col-form-label" for="member">Member</label>
        <div class="col-sm-10">
          <select class="form-select rounded-pill" name="" id="member" onchange="member1(this.value)">
            <option value="" selected>Choose Member</option>
            
          </select>
              </div>
        </div>
              </div>
        <!-- <div class="input-field col s12 browser-default">
          <input type="text" value="Person's Loanable amount "  name="" id="bal" disabled>
          <label for="bal">Standing Balance</label>
        </div> -->
        <div id="std"></div>
        <!-- <div id="amt-div"></div> -->
        <!-- <div class="input-field col s12 browser-default">
          <input type="number" data-length="2" name="" id="rate">
          <label for="rate">Rate</label>
        </div> -->
        

        <div class="row mb-3">
          <label for="inputText" class="col-sm-2 col-form-label">Amount</label>
          <div class="col-sm-10">
            <input type="number" id="amount"  oninput="check_amount()" class="form-control rounded-pill">
          </div>
          
          
        </div>
        <div id="amt-div"></div>
        <div id="new-div"></div>
        <div class="text-center mt-1"><p id="warning"></p></div>
        <div id="code-div"></div>
        <!-- <div class="input-field col s12 browser-default">
          <input type="text"  value="" id="code" disabled>
          <label for="code">Code</label>
        </div> -->
        <div id="date-div"></div>
        <!-- <div class="input-field col s12 browser-default">
          <input type="date" min="2022-12-23" value="" id="expiry" >
          <label for="expiry">Expiry Date</label>
        </div> -->
        <div id="btn-div"></div>
        <!-- <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center green" type="submit" name="action">Save Loan
    <i class="material-icons right">send</i>
  </button> -->
  </div>
	</form>
</div>


<script>
  page_title('New Loan');
  let loan_code = "";
  let u = "<?php echo $u; ?>"
  let inc = "<?php echo $oi; ?>"
  const member1 = (i) =>{
      $.ajax({
        type: "get",
        url: `${base_url}/api/loanAPI.php?sbal=${i}`,
        headers,
        dataType: "json",
        success: function (response) {
          console.log(response)
          $("#bal").val(nm.format(response.message));
          localStorage.setItem("b", response.message)
        }
      });
    }
    $('#addLoan').submit(e=> {
        e.preventDefault(); 
        $.ajax({
            type: "post",
            url: `${base_url}/api/loanAPI.php`,
            data: JSON.stringify({
                "member":$("#member").val(),
                "expiry":$("#expiry").val(),
                "code":$("#code").val(),
                "amount":$("#amount").val(),
                "rate":$("#rate").val()
            }),
            headers:headers,
            dataType: "json",
            success: function (response) {
                // console.log(response)
                if(response.status){
                    toast(response.message);
                    setTimeout(() => {
                        window.location = "/loans"
                    }, xtime);
                }else{
                    toast(response.error, 'danger');
                }
            }
        });
    });

    const group1 =(i) =>{
      // console.log(ty)
    // $("#group").on("change", ()=>{
      // e.preventDefault();
      // console.log($("#group").val())
      $.ajax({
        type: "get",
        url: `${base_url}/api/groupAPI.php?loanable=${i}`,
        headers,
        dataType: "json",
        success: function (response) {
          console.log(response)
          loan_code = `${response.group.g_code}-${u}-${number_with_zeros(inc, 3)}`;
          $("#code").val(loan_code);
          let x = "<option>Select Member</option>"
          for(let m of response.members){
            x+=`<option value="${m.m_id}">${m.m_code}</option>`
          }
          $("#member").html(x)
          // $('select').material_select();
        }
      });
    // })
      
    }
    

    $.ajax({
      type: "get",
      url:  `${base_url}/api/groupAPI.php`,
      headers,
      dataType: "json",
      success: function (response) {
        // console.log(response)
        try{
          let grp = []
          for(let r of response.group){
            grp.push({value:r.g_id, title: r.g_code})
          }
        }catch(TypeError){
          logout();
        }
        // console.log(grp)
        // Select({div:"grp-select", label:"Group",id:"group", value:"", options:grp})
      }
    });
    // const oninput =()=>{
    //   console.log('fjld')
    // }
    // member(5)
    Input({div:"date-div", value:"<?php echo $exp; ?>", label:"Expiry Date", type:"date", id:"expiry", dis:"disabled"})
    Input({div:"code-div", value:"", label:"Code", id:"code", dis:"disabled"})
    Input({div:'amt-div',value:'', label:'Rate', id:'rate', dlength:2, type:"number",})
    Input({div:"new-div", value:"", "label":"Amount Payable", id:"amnt", type:"number", dis:"disabled"})
    Input({div:"std",id:"bal",label:"Standing Balance", value:'',tp:"number",dis:"disabled"})
    Button({div:"btn-div", type:"submit", label:"Submit", icon:"send", btn:"success", })

    const check_amount = () =>{
      
      let c = $("#amount").val()
      let m  = localStorage.getItem("b")
      let r =  Number(c)-Number(m)
      
      if(Number(c)<Number(m)){
          
          $("#warning").text("Loan will be immediately active after creating it...")
          $("#warning").css({color:"green"})
      }else{
        // let ko = parseInt(Number($("#amount").val())
        // $("#warning").text(`An extra of atlease ${nm.format(r)} will be needed to make this loan active...`)
        // $("#warning").css({color:"red"})
      }
      
      // localStorage.removeItem("b")
    }

    $("#rate").on("input", ()=>{
      // console.log(Number($("#rate").val()))
      check_amount()
      $("#amnt").val(parseInt(Number($("#amount").val())*(1+Number($("#rate").val())/100)))
    })

    console.log($("#group").val())
    allow_url([2])
</script>