<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
$members = $help->query("select * from group_member where g_id=:grp", [':grp'=>$rt[2]]);
$ledgers = $help->query("select * from trans_types");
$weeks = $help->query("select * from weeks where g_id=:go order by w_id desc", [":go"=>$rt[2]]);
// $code = $help->get_last_id("t_id", "trans_action")+1;
$code = "TRS".time()."CMMF";

?>
<div class="row">
<h4 class="center-align">Make Transaction To Group</h4>
	<form class="col s12"id="addTransaction">
        <div class="input-field col s12">
          <select  id="w_id" name="m_id" >
            <option value="" selected>Select Week</option>
            <?php
                foreach($weeks->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['w_id'].">".$row['w_code']."</option>";
                }
            ?>
          </select>
          <label for="name">Week Details</label>
        </div>
		
        <div class="input-field col s12">
          <select  id="trans_type" name="trans_type" >
          <option value="" selected>Select Ledger Type</option>
            <?php
                foreach($ledgers->fetchAll(PDO::FETCH_ASSOC) as $row){
                    echo "<option value=".$row['ty_id'].">".$row['ty_name']."</option>";
                }
            ?>
          </select>
          <label for="name">Ledger Type</label>
        </div>
        <table>
            <tbody>
                <?php 
                // var_dump($members->fetchAll());
                $i = 0;
                foreach($members->fetchAll(PDO::FETCH_ASSOC) as $row){
                    
                    echo '<tr><td>'.$row['m_code'].'</td>';
                    echo '<td><div class="input-field col s12">
                    <input type="text" id="amount'.$i.'" value="0">
                    <label for="t_code">Amount</label>
                    <input type="hidden" id="id'.$i.'" value="'.$row['m_id'].'">
                  </div></td>';
                    echo "</tr>";
                    $i ++;
                }
                ?>
            </tbody>
        </table>
        
       
        <input type="hidden" name="" id="t_code" value="<?php echo $code; ?>">
        
        <div class="input-field col s12 browser-default">
            <textarea  name="" id="t_desc" class="materialize-textarea" cols="30" rows="5"></textarea>
            <label for="t_desc">Comment</label>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center green" type="submit" name="action">Add Transaction
    <i class="material-icons right">send</i>
  </button>
  </div>
	</form>
</div>

<script>
    page_title('New Transaction');
    let i = "<?php echo $i; ?>"
    let y = "<?php echo $rt[2]; ?>"
    $("#addTransaction").submit(e=>{
        e.preventDefault();
        //console.log(i*6)
        let m = []
        let n = []
        for(var x=0; x<i; x++){
            m.push($(`#amount${x}`).val());
            n.push($(`#id${x}`).val());
       
        }
        // console.log(m);
        // console.log(n)
        
        for(let x in m){  
            //console.log(n[x]) 
        $.ajax({
            type: "post",
            url: `${base_url}/api/transactionAPI.php`,
            data: JSON.stringify({
                "week":$("#w_id").val(),
                "member":n[x],
                "code":$("#t_code").val(),
                "trans_type":$("#trans_type").val(),
                "comment":$("#t_desc").val(),
                "amount":  Number(m[x]) 
            }),
            headers:headers,
            dataType: "json",
            success: function (response) {
                console.log(response)
                if(response.status){
                    // localStorage.setItem("msg", response.message)
                    toast(response.message, xtime)
                    setTimeout(() => {
                        window.location  = `/groups/${y}/make-transactions`
                    }, xtime);
                }else{
                    localStorage.setItem("msg", response.error)
                }
            }
            
        });
        
    // console.log(m)
    // console.log(n)
}
// toast(localStorage.getItem("msg"), xtime)
});
// $('#m_id').on('change', (e)=>{
//     console.log($('#m_id').val())
// })

// $('#trans_type').on('change', (e)=>{
//     console.log($('#trans_type').val())
// })
</script>