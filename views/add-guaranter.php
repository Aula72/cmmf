<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
$id = $help->get_loan_id($rt[2]);
$own = $help->get_member($id["m_id"]);

$bal = $id["lo_amount"] - $help->guarant_balance($id["lo_id"]);
?>
<div class="row">
    <div id="warnings" class="text-center m-3"></div>
	<form class="" id="addGuaranter">
		

        <div id="loan-num-div"></div>
       
        <div class="row mb-3">
        <label class="col-sm-2 col-form-label">Guaranter</label>
        <div class="col-sm-10">
          <select class="form-select rounded-pill" id="member" class="validate" required>
            <option value="" select>Select Member  From Group</option>
            <?php 
                foreach($help->get_guaranters($id['m_id'], $own["g_id"],$id['lo_amount']) as $row){
                    echo "<option value=".$row['m_id'].">".$help->get_member($row['m_id'])['m_code']."</option>";
                }
            ?>
		    
        </select>
            </div>
        </div>
        <div id="loanable"></div>
        <input type="hidden" name="" id="loan" value="<?php echo $id["lo_id"];?>">
        <div id="balc"></div>        
        <div id="amnt-div"></div>
        <div id="btn-div"></div>
  </button>
  </div>
	</form>
</div>


<script>
    page_title('Add Guaranter');
    let loan_amount = "<?php echo $bal; ?>"
    
    $("#addGuaranter").submit(e=>{
        e.preventDefault();
        $.ajax({
            method:"post",
            headers,
            url:`${base_url}/api/guaranterAPI.php`,
            data: JSON.stringify({
                amount: $("#amount").val(),
                member: $("#member").val(),
                loan: $("#loan").val(),
            }),
            dataType:"json",
            success:(response)=>{
                // console.log(response)
                try{
                    if(response.status){
                        toast(response.message)
                        setTimeout(() => {
                            window.location = `/loans/<?php echo $id['lo_code'];?>`;
                        }, xtime);
                    }else{
                        toast(response.error)
                    }
                }catch(TypeError){
                    logout();
                }
            }
        })
    })
    // Input({div:"loan-num-div"})
    Button({div:"btn-div", type:"submit",btn:'success', label:"Submit", icon:"send", dis:"disabled"})
    Input({div:"amnt-div", label:"Amount", type:"number", value:"", id:"amount"})
    Input({div:"loan-num-div", type:"text",label:"Loan Number", value:"<?php echo $id['lo_code'];?>", dis:"disabled"})
    Input({div:"balc", type:"text", label:"Maximum Amount to Guarant", value:"<?php echo $bal; ?>", dis:"disabled"})
    let loanable = ""
    $("#member").on("change", ()=>{
        // console.log($("#member").val())
        $.ajax({
            type: "get",
            url: `${base_url}/api/groupMemberAPI.php?id=${$("#member").val()}`,
            headers,
            dataType: "json",
            success: function (response) {
                loanable  = response.balance
                console.log(response)
                Input({div:"loanable", type:"text", dis:"disabled", value:loanable, id:"", label:"Net Worth"})
            }
        });
    })

    $("#amount").on("input",()=>{
        let m = "<?php echo $bal; ?>"
        $("#warnings").show();
        console.log(loanable - Number($("#amount").val()))
        if(Number(loan_amount)<Number($("#amount").val())){
            $("#warnings").html(`Required amount: ${loan_amount} is exceeded...`);
            $(":submit").attr("disabled", true);
        }else if(Number($("#amount").val())>loanable){
            // $("#warnings").html(`Required amount: ${loanable} is exceeded...`);
            $(":submit").attr("disabled", true);
        }else if(Number($("#amount").val())==0){
            $(":submit").attr("disabled", true);
            $("#warnings").hide();
        }else{
            // $("#warnings").html(`Operation can be performed...`);
            $(":submit").removeAttr("disabled");
        }
        
    })
    allow_url([2])
</script>