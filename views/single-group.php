<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
?>
<div class="col s12 m7">
    <h2 class="header" id="head">Load....</h2>
    <div class="card horizontal">
      <div class="card-image">
        <img src="/assets/img/account.png">
      </div>
      <div class="card-stacked">
        <div class="card-content">
          <ul>
            <li>
              <div class="row s12">
                <div class="col s6">Name</div>
                <div class="col s6" id="gname"></div>
              </div>
            </li>
            <li>
              <div class="row s12">
                <div class="col s6">Location</div>
                <div class="col s6" id="glocation">4</div>
              </div>
            </li>
            <li>
              <div class="row s12">
                <div class="col s6">Code</div>
                <div class="col s6" id="gcode">4</div>
              </div>
            </li>
            <li>
              <div class="row s12">
                <div class="col s6">Members</div>
                <div class="col s6" id="gmember">4</div>
              </div>
            </li>
          </ul>
        </div>
        <!-- <div class="card-action">
          <a href="#">This is a link</a>
        </div> -->
      </div>
    </div>
  </div>
      <h5 class="center-align">Members</h5>
      <table>
        <thead>
          <tr>
              <th>Code</th>
              <th>Name</th>
              <th>Phone</th>
          </tr>
        </thead>

        <tbody id="memberList">
          <tr>
            <td colspan=3 class="center-center">No members yet...!</td>
            
          </tr>
          
        </tbody>
      </table>
      <div class="fixed-action-btn">
        <a class="btn-floating btn-large green" href="/add-member">
          <i class="large material-icons">add</i>
        </a>
  <!-- <ul>
    <li><a class="btn-floating red"><i class="material-icons">insert_chart</i></a></li>
    <li><a class="btn-floating yellow darken-1"><i class="material-icons">format_quote</i></a></li>
    <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
    <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li>
  </ul> -->
</div>
<script>
  let op = "<?php echo $rt[2]; ?>";
  $.ajax({
    type: "get",
    url: `${base_url}/api/groupAPI.php?id=${op}`,
    headers:headers,    
    success: function (response) {
      // let p = JSON.parse(response)
      $('#head').text(`Group: ${response.group.g_code}`);
      $('#gname').text(response.group.g_name);
      $('#gmember').text(response.num_members);
      $('#gcode').text(response.group.g_code);
      $('#glocation').text(response.group.g_location);
      let g = ""
      if(response.members.length>0){
        for(let m of response.members){
          g += `<tr onclick="memb(${m.m_id})"><td>${m.m_code}</td><td>${m.m_lname} ${m.m_fname}</td><td>${m.m_phone}</td></tr>`;
        }
        $("#memberList").html(g);
      }
      
    }
  });

  const memb = (i) =>{
    localStorage.setItem('m_id', i);
    window.location = `/members/${i}`
  }
</script>
            

