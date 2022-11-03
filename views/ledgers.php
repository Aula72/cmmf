
<table>
    <tr>
        <th>Name</th>
        <th>Type</th>
    </tr>
    <tbody id="ledgerList">

    </tbody>
</table>
<div class="fixed-action-btn">
<a class="btn-floating btn-large green" href="/add-ledger">
    <i class="large material-icons">add</i>
</a>
</div>
<script>
    page_title('Ledgers');
    $.ajax({
        type: "get",
        url: `${base_url}/api/ledgerTypeAPI.php`,
        headers:headers,
        dataType: "json",
        success: function (response) {
            console.log(response)
            for(let x of response.ledger_type){
                $('#ledgerList').append(`<tr onclick="ledger(${x.ty_id})"><td>${x.ty_name}</td><td>${x.mult==1?'Credit':'Debit'}</td></tr>`);
            }
        }
    });
    const ledger = (i) =>{
        localStorage.setItem('ledger_id', i);
        window.location = `/ledgers/${i}`
    }
</script>