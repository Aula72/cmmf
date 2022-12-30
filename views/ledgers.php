<h4 class="center-align">Ledgers</h4>
<table class="table">
    <tr>
        <th>Name</th>
        <th>Type</th>
    </tr>
    <tbody id="ledgerList">

    </tbody>
</table>
<div id="a-div"></div>
<!-- <div class="fixed-action-btn">
<a class="btn-floating btn-large green" href="/add-ledger">
    <i class="large material-icons">add</i>
</a>
</div> -->
<script>
    page_title('Ledgers');
    $.ajax({
        type: "get",
        url: `${base_url}/api/ledgerTypeAPI.php`,
        headers:headers,
        dataType: "json",
        success: function (response) {
            // console.log(response)
            try{
                for(let x of response.ledger_type){
                    $('#ledgerList').append(`<tr onclick="ledger(${x.ty_id})"><td>${x.ty_name}</td><td>${x.mult==1?'Credit':'Debit'}</td></tr>`);
                }
            }catch(TypeError){
                logout();
            }
        }
    });
    const ledger = (i) =>{
        // localStorage.setItem('ledger_id', i);
        window.location = `/ledgers/${i}`
    }

    Anchor({div:"a-div", href:"/add-ledger",btn:"primary", text:"Add Ledger   ", icon:"file-plus"})
</script>