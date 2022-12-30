<div class="row">
  <div class="col-lg-8" >
    <div class="row" id="dash"></div>
    
</div>
</div>
<div class="col-lg-4">




<!-- <div class="fixed-action-btn">
<a class="btn-floating btn-large waves-effect waves-light red" href="/make-transactions"><i class="material-icons">add</i></a>
</div> -->

<script>
  page_title('Dashboard');

  // const go_to_page = (name) =>{
  //   window.location = name;
  // }

  $.ajax({
    type: "get",
    url: `${base_url}/api/dashAPI.php`,
    headers,
    dataType: "json",
    success: function (response) {
      // console.log(response)
      try{
      for(let m of response.dash){
        $("#dash").append(
          `<div class="col-xxl-4 col-md-6" onclick="go_to_page(['${m.url}'])">
              <div class="card info-card sales-card">
                <div class="card-body">
                  <h5 class="card-title">${m.name}<span></span></h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi ${m.img}"></i>
                    </div>
                    <div class="ps-3">
                      <h6>${m.num}</h6>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>`
        );
      }
      }catch(TypeError){
        window.location = "/login"
      }
  
    }
  });

  // $('.carousel.carousel-slider').carousel({
  //   fullWidth: true,
  //   indicators: true,
  // });
</script>