<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
$id = $help->get_loan_id($rt[2]);
$own = $help->get_member($id["m_id"]);

//guaranter balance
$g = $help->query("select ifnull(sum(amount), 0) as amount from guaranter_balance where lo_id=:lo", [":lo"=>$id["m_id"]]);
$g = $g->fetch(\PDO::FETCH_ASSOC);
// $xp = $help->new_worth($id["m_id"]);
// if($xp >= $id["lo_amount"]){

// }else{

// }
// echo json_encode($id);
$bal = -$help->ledger_sum($id["m_id"],$help->t_type("saving")) + round($id["lo_amount"]*(1+$id["lo_rate"]/100)) - $g["amount"];
// $bal = intval($id["lo_amount"]);

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
                $oppp = 0;
                foreach($help->get_guaranters($id['m_id'], $own["g_id"]) as $row){
                    if($row["t_amount"]!=0){
                        echo "<option value=".$row['m_id'].">".$help->get_member($row['m_id'])['m_code']."</option>";
                        $oppp +=1;
                    }
                    
                }
                if($oppp==0){
                    echo "<option value=''>No possible guaranter Available</option>";
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
                        toast(response.error, 'danger')
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