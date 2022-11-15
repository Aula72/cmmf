<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
$id = $help->get_loan_id($rt[2]);

$yu = $help->query("select * from loans where lo_id=:id",[":id"=>$id["lo_id"]]);
$yu = $yu->fetch(\PDO::FETCH_ASSOC);

$y = $help->query("select sum(amount) as amt from loan_payment where lo_id=:id",[":id"=>$id["lo_id"]]);
$y = $y->fetch(\PDO::FETCH_ASSOC);

$bal = (1 +intval($yu["lo_rate"])/100)*intval($yu["lo_amount"]) - intval($y["amt"]);

// var_dump($id);
?>
<h4 class="center-align">Make Payment</h4>
<div class="row">
	<form class="col s12" id="addPayment">
        <div class="input-field">
            <!-- <i class="material-icons prefix">money</i> -->
            <input id="" type="number" class="validate" value="<?php echo $bal; ?>" disabled>
            <label for="icon_prefix">Due Amount</label>
        </div>
        <div class="input-field">
            <i class="material-icons prefix">money</i>
            <input id="amount" type="number" class="validate" required>
            <label for="icon_prefix">Amount</label>
        </div>
        <div class="input-field  browser-default">
            <textarea  name="" id="comment" class="materialize-textarea" cols="30" rows="5"></textarea>
            <label for="comment">Comment</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center green" type="submit" name="action" id="act">Make Payment
    <i class="material-icons right">send</i>
  </button>
  </div>
	</form>
</div>

<script>
    page_title('Loan Payment');
    let bal = "<?php echo $bal; ?>"
    $("#amount").on("input", e=>{
        e.preventDefault();
        if(Number($("#amount").val())>Number(bal)){
            toast(`Required balance: ${bal}/= please enter a lesser amount`)
            $("#act").attr("disabled", true);
        }else{
            $("#act").removeAttr("disabled");
        }
    })
    $("#addPayment").submit( (e) => { 
        e.preventDefault();
        $.ajax({
            type: "post",
            url: `${base_url}/api/loanPaymentAPI.php`,
            data: JSON.stringify({
                loan:`<?php echo $id["lo_id"]; ?>`,
                amount: $("#amount").val(),
                comment: $("#comment").val()
            }),
            headers,
            dataType: "json",
            success: function (response) {
                if(response.status){
                    toast(response.message, xtime)
                    setTimeout(() => {
                        window.location = `/loans/<?php echo $rt[2]; ?>`
                    }, xtime);                    
                }else{
                    toast(response.error)
                }
            }
        });
    });
    
</script>