<script>
    $(document).ready(()=>{
        $.get(`${base_url}/api/user_logout.php?token=${localStorage.getItem('token')}`,(data, status)=>{
            // console.log(data)
            localStorage.clear();
            window.location = "/login";
        });
        
   
    })
</script>