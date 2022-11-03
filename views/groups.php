<table class="striped">
    <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Location</th>
            <th></th>
        </tr>
    </thead>
    <tbody id="groupList">

    </tbody>

</table>
<div class="fixed-action-btn">
  <a class="btn-floating btn-large green" href="/add-group">
    <i class="large material-icons">add</i>
  </a>
  <!-- <ul>
    
    <li><a class="btn-floating green" href="/add-group"><i class="material-icons">person_add</i></a></li>
    <li><a class="btn-floating yellow darken-1"><i class="material-icons">format_quote</i></a></li>
    <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
    <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li>
  </ul> -->
</div>
<script>
    $.ajax({
        type: "get",
        url: `${base_url}/api/groupAPI.php`,
        header:headers,
        dataType: "json",
        success: function (response) {
            console.log(response)
            for(let p of response.group){
                $('#groupList').append(`<tr class="modal-trigger" onclick="go_to(${p.g_id})"><td>${p.g_code}</td><td>${p.g_name}</td><td>${p.g_location}</td></tr>`);
            }
        }
    });

   const go_to = (i) =>{
        localStorage.setItem('g_id', i);
        window.location = `groups/${i}`
   }
    
   page_title('Groups');

</script>
