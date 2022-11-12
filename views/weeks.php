<h4 class="center-align">Weeks</h4>
<table>
        <thead>
          <tr>
              <th>Week </th>
              <th>Group</th>
              <th>Date</th>
              <!-- <th></th> -->
          </tr>
        </thead>

        <tbody id="list_weeks">
          
        </tbody>
      </table>
      <div class="fixed-action-btn">
  <a class="btn-floating btn-large green" href="/add-week">
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
    page_title('Weeks');
    $.get(`${base_url}/api/weekAPI.php`,(data, status)=>{
        let w = data;
        let row = '';
        if(w.weeks.length){
        for(var x of w.weeks){
            row += `<tr >
            <td>${x.w_code}</td>
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
    });

    const week = (id) =>{
        alert(id);
    }
    const view_week =(id)=>{
        alert(id);
    }
</script>