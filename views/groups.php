<!-- <h4 class="center-align">Groups</h4> -->
<table class="table striped">
    <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Location</th>
            <th id="add-div">

            </th>
            <th>
                <button class="btn btn-outline-primary rounded-pill btn-sm" onclick="print_now();">Print <i class="bi bi-printer"></i></button>
            </th>
        </tr>
    </thead>
    <tbody id="groupList">

    </tbody>

</table>
<!-- <div class="fixed-action-btn">
  <a class="btn-floating btn-large green" href="/add-group">
    <i class="large material-icons">add</i>
  </a> -->
  <!-- <ul>
    
    <li><a class="btn-floating green" href="/add-group"><i class="material-icons">person_add</i></a></li>
    <li><a class="btn-floating yellow darken-1"><i class="material-icons">format_quote</i></a></li>
    <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
    <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li>
  </ul> -->
<!-- </div> -->
<script>
    $.ajax({
        type: "get",
        url: `${base_url}/api/groupAPI.php`,
        headers,
        dataType: "json",
        success: function (response) {
            // console.log(response)
            try{
            if(response.group.length){
                // let ur = 'groups'
                for(let p of response.group){
                    // let iu = ur+"/"+p.g_id
                    // console.log(iu)
                    $('#groupList').append(`<tr  >
                        <td>${p.g_code}</td>
                        <td>${p.g_name}</td>
                        <td>${p.g_location}</td>
                        <td><button class="btn btn-success btn-sm rounded-pill" onclick="go_to_page(['groups', ${p.g_id}])">Details <i class="bi bi-eye"></button></td>
                        <td><button class="btn btn-primary btn-sm rounded-pill" onclick="localStorage.setItem('g_id', ${p.g_id} );go_to_page(['add-member']);">Add Member <i class="bi bi-person-plus"></button></td>
                        <td><button class="btn btn-warning btn-sm rounded-pill" onclick="localStorage.setItem('g_id', ${p.g_id} );go_to_page(['groups/${p.g_id}/make-transactions']);">Make Transaction <i class="bi bi-currency-dollar"></button></td>
                        </tr>`);
                }
            }else{
                let t = confirm("No groups yet, add first group...")
                if(t){
                    window.location = "add-group";
                }else{
                    window.history.go(-1)
                }
            }
            }catch(TypeError){
                logout();
            }
            
        }
    });

//    const go_to = (i) =>{
//         localStorage.setItem('g_id', i);
//         window.location = `groups/${i}`
//    }
    
    page_title('Groups');
    Anchor({div:"add-div", href:"/add-group", btn:"secondary", text:"Add Group"});
    
</script>
