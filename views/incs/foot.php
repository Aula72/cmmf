 </div>
 
  <script>
  if(token){
		$.ajax({
			type: "get",
			url: `${base_url}/api/isLoggedIn.php`,
			headers:{
				"auth":token
			},
			dataType: "json",
			success: function (response) {
				if(response.error){
          if(window.location.pathname!='/login' || window.location.pathname!='/otp'){
					  Materialize.toast('You are not logged...', xtime);
            setTimeout(() => {
              window.location = '/login'
            }, xtime);            
				  }
			  }
      }
		});
  }
</script>
  </body>  
  </html>

 