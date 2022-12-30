<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
?>
<div class="row mb-3">

          <ul class="list-group">
            <li class="list-group-item">
              <div class="row">
                <div class="col-lg-6">Name</div>
                <div class="col-lg-6" id="gname"></div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row">
                <div class="col-lg-6">Location</div>
                <div class="col-lg-6" id="glocation">4</div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row">
                <div class="col-lg-6">Code</div>
                <div class="col-lg-6" id="gcode">4</div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row">
                <div class="col-lg-6">Members</div>
                <div class="col-lg-6" id="gmember">4</div>
              </div>
            </li>
            <li class="list-group-item">
              <div class="row">
                <div class="col-lg-6">
                  <a href="/add-member" onclick="localStorage.setItem('g_id', <?php echo $rt[2];?>);"class="btn">Click to Add Member to Group</a>
                </div>
                <div class="col-lg-6">
                  <a class="btn" href="/groups/<?php echo $rt[2]; ?>/make-transactions">Make Transaction  <i class="bi bi-money"></i></a>
                </div>
              </div>
              <p>
                
              </p>
            </li>
          </ul>
        </div>
        <!-- <div class="card-action">
          <a href="#">This is a link</a>
        </div> -->
      <!-- </div>
    </div> -->
  </div>
      <h5 class="text-center">Members</h5>
      <table class="table">
        <thead>
          <tr>
              <th>Code</th>
              <th>Name</th>
              <th>Phone</th>
              <th>NIN</th>
          </tr>
        </thead>

        <tbody id="memberList">
          <tr>
            <td colspan=4 class="text-center">No members yet...!</td>
            
          </tr>
          
        </tbody>
      </table>
      <div class="fixed-action-btn">
  <a class="btn-floating btn-large green">
    <i class="large material-icons">edit</i>
  </a>
  <ul>
    <li><a class="btn-floating red" href="/add-member"><i class="material-icons">person_add</i></a></li>
    <li id="adds"><a class="btn-floating yellow darken-1" href="/groups/<?php echo $rt[2]; ?>/make-transactions"><i class="material-icons">money</i></a></li>
    <!-- <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li> -->
    <!-- <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li> -->
  </ul>
</div>
<script>
  page_title('Loading...');
  let op = "<?php echo $rt[2]; ?>";
  $.ajax({
    type: "get",
    url: `${base_url}/api/groupAPI.php?id=${op}`,
    headers:headers,    
    success: function (response) {
      // let p = JSON.parse(response)
      try{
        $('#head').text(`Group: ${response.group.g_code}`);
        $('#gname').text(response.group.g_name);
        $('#gmember').text(response.num_members);
        $('#gcode').text(response.group.g_code);
        $('#glocation').text(response.group.g_location);
        page_title(response.group.g_code);
        let g = ""
        if(response.members.length>0){
          for(let m of response.members){
            g += `<tr >
                  <td>${m.m_code}</td>
                  <td>${m.m_lname} ${m.m_fname}</td>
                  <td>${m.m_phone}</td>
                  <td>${m.m_nin}</td>
                  <td><button onclick="memb(${m.m_id})" class="btn btn-sm btn-primary">Details</button></td>
                  <td><a href="/members/${m.m_id}/add-transaction" class="btn btn-sm btn-success">Make Transaction</a></td>                
                  <td><a href="/members/${m.m_id}/edit" class="btn btn-sm btn-info">Edit</a></td>
                </tr>`;
          }
          $("#memberList").html(g);
        }else{
          $("#adds").hide();
        }
      }catch(TypeError){
        logout();
      }
    }
  });

  const memb = (i) =>{
    localStorage.setItem('m_id', i);
    window.location = `/members/${i}`
  }
</script>
            

