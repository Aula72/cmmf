
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>           
        </tr>
    </thead>
    <tbody id="adminList">

    </tbody>
</table>

<div class="fixed-action-btn">
<a class="btn-floating btn-large red" href="/add-admin">
    <i class="large material-icons">add</i>
</a>
</div>
<script>
    $.ajax({
        type: "GET",
        url: `${base_url}/api/userAPI.php`,
        headers:headers,
        dataType: "json",
        success: function (response) {
            console.log(response)
            for(let m of response.admin){
                $("#adminList").append(`<tr class=${m.status==1?"":"red"}><td>${m.lname} ${m.fname}</td><td>${m.mail}</td></tr>`);
            }
        }
    });
</script>