
<div id="table-div"></div>

<?php 
    echo $_SERVER['QUERY_STRING'];
?>

<script>
    page_title("Activity Logs")

    $.ajax({
        type: "get",
        url: `${base_url}/api/user.php?type=logs&&num=100`,
        headers,
        dataType: "json",
        success: function (response) {
            // console.log(response)
            let head = ["No.", "User", "Activity", "Date"]
            let body = [];
            try{
                for(let m of response.logs){
                    body.push([m.log_id, m.user_id, m.lo_statement, m.created_at]);
                }
            }catch(TypeError){
                logout();
            }
            Table({
                div: "table-div",
                head,
                body
            })
        }
    });
</script>