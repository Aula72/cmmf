
<section class="section">
      <div class="row">
<!-- <h4 class="center-align">Weeks</h4> -->
<table class="table">
        <thead>
          <tr>
              <th>Week </th>
              <th>F. Year</th>
              <th>Group</th>
              <th>Date</th>
              <th colspan=3>
              <a class="btn btn-outline-success rounded-pill btn-sm" href="/add-week">Add Week  <i class="bi bi-calendar-plus"></i></a>
                <a class="btn btn-outline-warning rounded-pill btn-sm" onclick="add_year()">Add Financial Year  <i class="bi bi-plus"></i></a>
                <button class="btn btn-outline-primary rounded-pill btn-sm" onclick="print_now();">Print <i class="bi bi-printer"></i></button>
                </th>
              <!-- <th></th> -->
          </tr>
        </thead>

        <tbody id="list_weeks">
          
        </tbody>
      </table>
      <!-- <div class="fixed-action-btn">
  <a class="btn-floating btn-large green" >
    <i class="large material-icons">more</i>
  </a>
  <ul>
    
    <li><a class="btn-floating green" href="/add-week"><i class="material-icons">add</i></a></li>
    <li><a class="btn-floating yellow darken-1" onclick="add_year()"><i class="material-icons">schedule</i></a></li> -->
    <!-- <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
    <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li> -->
  <!-- </ul>
</div> -->
</div>
</section>
<script>
    page_title('Weeks');
    $.ajax({
      type: "get",
      url: `${base_url}/api/weekAPI.php`,
      headers,
      dataType: "json",
      success: function (response) {
        try{
          let w = response;
          console.log(w)
          let row = '';
          if(w.weeks.length){
          for(var x of w.weeks){
              row += `<tr >
              <td>${x.w_code}</td>
              <td>${x.year}</td>
              <td>${x.g_code}</td>
              <td >${x.w_date}</td>
              <td><a href="/weeks/${x.w_id}/week-transactions" class="btn btn-outline-success rounded-pill btn-sm">Make Transaction <i class="bi bi-eye"></i></a href="/weeks/{$po[2]}/week-transactions"></td>
            </tr>`;
          }
            $('#list_weeks').html(row);
          }else{
            let p = confirm("No weeks yet, to added first week first add group")
            if(p){
              window.location = "/add-week";
            }else{
              window.history.go(-1)
            }
          }
        }catch(TypeError){
          logout();
        }
      }
    });
    // $.get(`${base_url}/api/weekAPI.php`,(data, status)=>{
        
    // });

    const week = (id) =>{
        alert(id);
    }
    const view_week =(id)=>{
        alert(id);
    }

    const add_year = () =>{
      let p = prompt("Add New Financial Year")
      if(p){
        $.ajax({
          type: "post",
          url: `${base_url}/api/weekAPI.php?year=${p}`,
          headers,
          dataType: "json",
          success: function (response) {
            try{
              if(response.status==1){
                toast(response.message, xtime)
              }else{
                toast(response.message, xtime)
                add_year();
              }
            }catch(TypeError){
              logout();
            }
            
          }
        });
      }
    }
</script>