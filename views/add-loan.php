<?php 
$mem  = $help->query("select * from group_member");
$code = intval($help->get_last_id("lo_id", "loans"))+1;
if($code<1000){    
    if($code<10){
        $code = '000'.$code;
    }else if($code>10 && $code<99){
        $code = "00".$code;
    }else{
        $code = "0".$code;
    }
}
$code = "LN".$code.date("dmY");
?>
<div class="row">
	<form id="addLoan">
		<div class="input-field col s12">
          <select name="" id="member">
            <option value="" selected>Choose Member</option>
            <?php
                foreach($mem->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['m_id'].">".$row['m_fname']." ".$row['m_lname']."  (".$row['m_code'].")</option>";
                }
            ?>
          </select>
          <label for="member">Member Details</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="number" maxLength="2" name="" id="rate">
          <label for="rate">Rate</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="number"  value="" id="amount" >
          <label for="amount">Amount</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="text" max-length="2" value="<?php echo $code; ?>" id="code" disabled>
          <label for="code">Code</label>
        </div>
        <div class="input-field col s12 browser-default">
          <input type="date"  value="" id="expiry" >
          <label for="expiry">Expiry Date</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center" type="submit" name="action">Save Loan
    <i class="material-icons right">send</i>
  </button>
  </div>
	</form>
</div>


<script>
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
</script>