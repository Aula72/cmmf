<div class="row">
	<form id="addLedger">
		<div class="input-field col s12">
          <input id="name" type="text"  class="validate">
          <label for="name">Ledger Name</label>
        </div>
        <div class="input-field col s12 browser-default">
          <select id="mult" class="validate" required>
            <option value="1">Increase</option>
            <option value="-1">Decrease</option>
		    <label>Type</label>
        </select>
        </div>
        <div class="col s12 align-center">
  	<button class="btn waves-effect waves-light align-center" type="submit" name="action">Add Ledger
    <i class="material-icons right">send</i>
  </button>
  </div>
	</form>
</div>

<script>
    $('#addLedger').submit((e)=>{
        e.preventDefault();
        $.ajax({
            type: "post",
            url: `${base_url}/api/ledgerTypeAPI.php`,
            data: JSON.stringify({
                name:$("#name").val(),
                mult: $("#mult").val()
            }),
            headers:{
                "content-type":"application/json",
                "auth": token
            },
            dataType: "json",
            success: function (response) {
                if(response.status==1){
                    Materialize.toast(response.message,xtime)
                    setTimeout(() => {
                        window.location = '/ledgers'
                    }, xtime);
                }else{
                    Materialize.toast(response.error,xtime)
                }
            }
        });
    })
    
</script>

