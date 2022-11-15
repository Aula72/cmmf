
<div class="carousel carousel-slider">
    <a class="carousel-item" href="#one!"><img src="/assets/img/loans.png"></a>
    <a class="carousel-item" href="#two!"><img src="/assets/img/users.png"></a>
    <a class="carousel-item" href="#three!"><img src="/assets/img/weeks.png"></a>
    <a class="carousel-item" href="#four!"><img src="/assets/img/groups.png"></a>
</div>
<div class="row" id="dash">
	
	
	
	</div>
</div>

<!-- <div class="fixed-action-btn">
<a class="btn-floating btn-large waves-effect waves-light red" href="/make-transactions"><i class="material-icons">add</i></a>
</div> -->

<script>
  page_title('Dashboard');

  const go_to_page = (name) =>{
    window.location = name;
  }

  $.ajax({
    type: "get",
    url: `${base_url}/api/dashAPI.php`,
    headers,
    dataType: "json",
    success: function (response) {
      // console.log(response)
      for(let m of response.dash){
        $('#dash').append(`<div class="col s6">
		<div class="card" onclick="go_to_page('${m.url}')">
    
            <div class="card-image">
              <img src="${m.img}">
              <span class="card-title" style="font-size: 25px; color: red; font-weight: bold;">${m.num}</span>
          </div>  
          <div class="card-content">
              <p>${m.name} </p>
            </div>          
    </div>    
	</div>`)
      }
      $('#dash').append(`<div class="col s6">
		<div class="card" onclick="go_to_page('/logout')">
    
            <div class="card-image">
              <img src="/assets/img/logout.png">
              <span class="card-title" style="font-size: 25px; color: red; font-weight: bold;"></span>
          </div>  
          <div class="card-content">
              <p>Log Me Out </p>
            </div>          
    </div>    
	</div>`)
    }
  });

  $('.carousel.carousel-slider').carousel({
    fullWidth: true,
    indicators: true,
  });
</script>