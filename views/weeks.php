<h4 class="center-align">Weeks</h4>
<table>
        <thead>
          <tr>
              <th>Week </th>
              <th>F. Year</th>
              <th>Group</th>
              <th>Date</th>
              <!-- <th></th> -->
          </tr>
        </thead>

        <tbody id="list_weeks">
          
        </tbody>
      </table>
      <div class="fixed-action-btn">
  <a class="btn-floating btn-large green" >
    <i class="large material-icons">more</i>
  </a>
  <ul>
    
    <li><a class="btn-floating green" href="/add-week"><i class="material-icons">add</i></a></li>
    <li><a class="btn-floating yellow darken-1" onclick="add_year()"><i class="material-icons">schedule</i></a></li>
    <!-- <li><a class="btn-floating green"><i class="material-icons">publish</i></a></li>
    <li><a class="btn-floating blue"><i class="material-icons">attach_file</i></a></li> -->
  </ul>
</div>
<script>
    page_title('Weeks');
    $.ajax({
      type: "get",
      url: `${base_url}/api/weekAPI.php`,
      headers,
      dataType: "json",
      success: function (response) {
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
            if(response.status==1){
              toast(response.message, xtime)
            }else{
              toast(response.message, xtime)
              add_year();
            }
            
          }
        });
      }
    }
</script>