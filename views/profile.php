

<script>
    let id = "<?php echo $_GET['admin']; ?>"
    console.log(id)
    $.ajax({
        type: "get",
        url: `${base_url}/api/userAPI.php?id=${id}`,
        headers,
        dataType: "json",
        success: function (response) {
            console.log(response)
            page_title(`${response.admin.fname} ${response.admin.lname}`)
        }
    });
</script>