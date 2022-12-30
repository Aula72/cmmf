 </div>
 
  <script>
	
	// $(document).on("touchmove",e=>{
	// 	console.log(e)
 	// 	setTimeout(() => {
	// 		localStorage.setItem("token", '')
	// 	}, xtime);
	// })
	// $(document).on("mousemove",e=>{
	// 	console.log(e)
 	// 	setTimeout(() => {
	// 		localStorage.setItem("token", '')
	// 	}, xtime);
	// })
  if(token){
		$.ajax({
			type: "get",
			url: `${base_url}/api/isLoggedIn.php`,
			headers,
			dataType: "json",
			success: function (response) {
				if(response.error){
          		if(window.location.pathname!='/login' || window.location.pathname!='/otp'){
					// let x  = prompt("Enter OTP")	
				toast('You are not logged...', xtime);
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

 