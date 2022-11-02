<div class="fixed-action-btn">
  <a class="btn-floating btn-large red" href="/add-loan">
    <i class="large material-icons">add</i>
  </a>
</div>

<script>
    $.ajax({
        type: "get",
        url: `${base_url}/api/loanAPI.php`,
        headers:{
            auth:token
        },
        dataType: "json",
        success: function (response) {
            console.log(response);
        }
    });
</script>