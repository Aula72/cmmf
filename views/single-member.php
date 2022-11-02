<div class="row">
  <ul class="collection">
    <li class="collection-item">
      <i class="material-icons secondary-content">person</i>
      <div>Aula Simon</div>
    </li>
    <li class="collection-item">
      <i class="material-icons secondary-content"></i>
      <div>0788227244</div>
    </li>
    <li class="collection-item">
      <i class="material-icons secondary-content"></i>
      <div>Male</div>
    </li>
    <li class="collection-item">
      <i class="material-icons secondary-content"></i>
      <div></div>
    </li>
    <li class="collection-item">
      <i class="material-icons secondary-content"></i>
      <div>CF090403KOOW</div>
    </li>
    <li class="collection-item">
      <i class="material-icons secondary-content"></i>
      <div></div>
    </li>
    <li class="collection-item">
      <i class="material-icons secondary-content"></i>
      <div></div>
    </li>
    <li class="collection-item">
      <i class="material-icons secondary-content"></i>
      <div></div>
    </li>
    <li class="collection-item">
      <i class="material-icons secondary-content"></i>
      <div></div>
    </li>
  </ul>
</div>



      <table>
        <thead>
          <tr>
              <th>Name</th>
              <th>Item Name</th>
              <th>Item Price</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>Alvin</td>
            <td>Eclair</td>
            <td>$0.87</td>
          </tr>
          <tr>
            <td>Alan</td>
            <td>Jellybean</td>
            <td>$3.76</td>
          </tr>
          <tr>
            <td>Jonathan</td>
            <td>Lollipop</td>
            <td>$7.00</td>
          </tr>
        </tbody>
      </table>

<script>
  let m_id = localStorage.getItem("m_id");
  $.ajax({
    type: "get",
    url: `${base_url}/api/groupMemberAPI.php?id=${m_id}`,
    headers:headers,
    dataType: "json",
    success: function (response) {
      console.log(response)
    }
  });
</script>
            

