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
        
        <div id="due-div"></div>
        <div id="amount-div"></div>
        <div id="com-div"></div>
        
        <div id="btn-div"></div>
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
        console.log(JSON.stringify({
                loan:`<?php echo $id["lo_id"]; ?>`,
                amount: $("#amount").val(),
                comment: $("#comment").val()
            }))
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
                try{
                    if(response.status){
                        toast(response.message, xtime)
                        setTimeout(() => {
                            window.location = `/loans/<?php echo $rt[2]; ?>`
                        }, xtime);                    
                    }else{
                        toast(response.error)
                    }
                }catch(TypeError){
                    logout();
                }
            }
        });
    });
    TextArea({div:"com-div", label:"Comment", placeholder:"", id:"comment"})
    Button({div:"btn-div", icon:"send", label:"Submit", type:"submit", btn:"success"})
    Input({div:"due-div", label:"Due Amount", value:bal, dis:"disabled"})
    Input({div:"amount-div", label:"Amount", value:"", id:"amount", type:"number"})

    allow_url([2])
</script>