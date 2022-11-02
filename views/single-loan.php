<?php 
$rt = explode("/",$_SERVER['REQUEST_URI']);
?>
<h3 id="name" class="center-align"></h3>
<h4 class="center-align">Owner's Details</h4>
<h4 class="center-align">Recent Payments</h4>
<h4 class="center-align">Guaranter(s)</h4>
<h4 class="center-align">Fines</h4>
<script>
    let id = "<?php echo $rt[2]; ?>";
    $.ajax({
        type: "get",
        url: `${base_url}/api/loanAPI.php?id=${id}`,
        headers,
        dataType: "json",
        success: function (response) {
            console.log(response)
            $("#name").text(response.loan.lo_code)
        }
    });
</script>