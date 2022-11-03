<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
$id = $help->get_loan_id($rt[2]);
// $id = $id?$id:$rt[2];
// var_dump($help->get_loan_id($rt[2]));
// echo $id['lo_id'];

?>

<div class="row">
	<form id="addGuaranter">
		<div class="input-field col s12">
          <input id="name" type="text"  class="validate" value="<?php echo $id['lo_code'];?>" disabled>
          <label for="name">Loan Number</label>
        </div>
        <div class="input-field col s12 browser-default">
          <select id="member" class="validate" required>
            <?php 
                foreach($help->get_guaranters($id['m_id'], $id['lo_amount']) as $row){
                    echo "<option value=".$row['m_id'].">".$help->get_member($row['m_id'])['m_fname']." ".$help->get_member($row['m_id'])['m_fname']."(".$help->get_member($row['m_id'])['m_code'].")</option>";
                }
            ?>
		    
        </select>
        <label>Guaranter</label>
        </div>
        <input type="hidden" name="" id="loan" value="<?php echo $id["lo_id"];?>">
        <div class="input-field col s12">
          <input id="amount" type="number"  class="validate" value="">
          <label for="name">Amount</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center" type="submit" name="action">Add Ledger
    <i class="material-icons right">send</i>
  </button>
  </div>
	</form>
</div>


<script>
    page_title('Add Guaranter');
    $("#amount").on("input",()=>{
        console.log($("#amount").val())
    })
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
                console.log(response)
                if(response.status){
                    Materialize.toast(response.message, xtime)
                    setTimeout(() => {
                        window.location = `/loans/<?php echo $id['lo_code'];?>`;
                    }, xtime);
                }else{
                    Materialize.toast(response.error, xtime)
                }
            }
        })
    })
    
</script>