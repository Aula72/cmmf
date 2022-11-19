<?php 
$mem  = $help->query("select * from grouping");
$year  = $help->query("select * from finanial_year order by y_id desc limit 1");
// $m = $mem->fetch(\PDO::FETCH_ASSOC);
$y = $year->fetch(\PDO::FETCH_ASSOC);

$u = $y["name"]>10?$y["name"]:"0".$y["name"];
// $code = "LN".$code.date("mY");
$oi = $help->get_last_id("lo_id","loans")+1;
// $code = $m["g_code"].$u.$io;

// $g = $help->number_with_zeros(3, 4);
?>
<div class="row">
<h4 class="center-align">Add New Loan</h4>
	<form class="col s12" id="addLoan">
		<div class="col s12">
    <div class="input-field col s6 browser-default">
          <select name="" id="group" onchange="group1(this.value)">
            <option value="" selected>Choose Member</option>
            <?php
                foreach($mem->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['g_id'].">".$row['g_code']."</option>";
                }
            ?>
          </select>
          <label for="member">Group</label>
        </div>
        <div class="input-field col s6 browser-default">
          <select name="" id="member" onchange="member1(this.value)">
            <option value="" selected>Choose Member</option>
            
          </select>
          <label for="member">Member</label>
        </div>
              </div>
        <div class="input-field col s12 browser-default">
          <input type="text" value="Person's Loanable amount "  name="" id="bal" disabled>
          <label for="bal">Standing Balance</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="number" data-length="2" name="" id="rate">
          <label for="rate">Rate</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="number" data-length='7'  value="" id="amount" >
          <label for="amount">Amount</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="text"  value="" id="code" disabled>
          <label for="code">Code</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="date" min="2022-12-23" value="" id="expiry" >
          <label for="expiry">Expiry Date</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center green" type="submit" name="action">Save Loan
    <i class="material-icons right">send</i>
  </button>
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
          // console.log(response)
          $("#bal").val(nm.format(response.message));
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
                    Materialize.toast(response.message, xtime);
                    setTimeout(() => {
                        window.location = "/loans"
                    }, xtime);
                }else{
                    Materialize.toast(response.error, xtime);
                }
            }
        });
    });

    const group1 =(i) =>{
      // console.log(ty)
      $.ajax({
        type: "get",
        url: `${base_url}/api/groupAPI.php?id=${i}`,
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
          $('select').material_select();
        }
      });
    }

    // member(5)
</script>