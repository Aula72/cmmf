<div class="row">
	<form class="col s12" id="addLedger">		
        <div id="name-div"></div>
        <div id="type-div"></div>
        <div id="btn-div"></div>        
  	</form>
</div>

<script>
    page_title('Add Ledger');
    $('#addLedger').submit((e)=>{
        e.preventDefault();
        $.ajax({
            type: "post",
            url: `${base_url}/api/ledgerTypeAPI.php`,
            data: JSON.stringify({
                name:$("#name").val(),
                mult: $("#mult").val()
            }),
            headers:headers,
            dataType: "json",
            success: function (response) {
                try{
                    if(response.status==1){
                        toast(response.message)
                        setTimeout(() => {
                            window.location = '/ledgers'
                        }, xtime);
                    }else{
                        toast(response.error,'danger')
                    }
                }catch(TypeError){
                    logout();
                }
            }
        });
    })
    
    Button({div:"btn-div", label:"Submit", icon:"send", type:"submit", btn:"success"})
    Input({div:"name-div", label:"Name", id:"name", value:"", type:"text"})
    Select({div:"type-div", id:"mult",label:"Type", options:[{value:1, title:"Increase"},{value:-1, title:"Decrease"}], })
</script>

