
<div class="row">
	
	<div class="col s6">
		<div class="card blue-grey darken-1" onclick="go_to_page('/weeks')">
        <div class="card-content white-text">
          <span class="card-title">Weeks</span>
          <p>I am a very simple card. I am good at containing small bits of information.
          I am convenient because I require little markup to use effectively.</p>
        </div>
        <div class="card-action">
          <a href="#">This is a link</a>
          <a href="#">This is a link</a>
        </div>
      </div>
	</div>
	<div class="col s6">
		<div class="card blue-grey darken-1" onclick="go_to_page('/groups')">
        <div class="card-content white-text">
          <span class="card-title">Groups</span>
          <p>I am a very simple card. I am good at containing small bits of information.
          I am convenient because I require little markup to use effectively.</p>
        </div>
        <div class="card-action">
          <a href="#">This is a link</a>
          <a href="#">This is a link</a>
        </div>
      </div>
	</div>
	<div class="col s6">
		<div class="card blue-grey darken-1" onclick="go_to_page('/savings')">
        <div class="card-content white-text">
          <span class="card-title">Savings</span>
          <p>I am a very simple card. I am good at containing small bits of information.
          I am convenient because I require little markup to use effectively.</p>
        </div>
        <div class="card-action">
          <a href="#">This is a link</a>
          <a href="#">This is a link</a>
        </div>
      </div>
	</div>
	<div class="col s6">
		<div class="card blue-grey darken-1" onclick="go_to_page('/admins')">
        <div class="card-content white-text">
          <span class="card-title">Admin</span>
          <p>I am a very simple card. I am good at containing small bits of information.
          I am convenient because I require little markup to use effectively.</p>
        </div>
        <div class="card-action">
          <a href="#">This is a link</a>
          <a href="#">This is a link</a>
        </div>
      </div>
	</div>
	<div class="col s6">
		<div class="card blue-grey darken-1" onclick="go_to_page('/reports')">
        <div class="card-content white-text">
          <span class="card-title">Reports</span>
          <p>I am a very simple card. I am good at containing small bits of information.
          I am convenient because I require little markup to use effectively.</p>
        </div>
        <div class="card-action">
          <a href="#">This is a link</a>
          <a href="#">This is a link</a>
        </div>
      </div>
	</div>
  <div class="col s6">
		<div class="card blue-grey darken-1" onclick="go_to_page('/logout')">
        <div class="card-content white-text">
          <span class="card-title">Log Me Out</span>
          <p>I am a very simple card. I am good at containing small bits of information.
          I am convenient because I require little markup to use effectively.</p>
        </div>
        <div class="card-action">
          <a href="#">This is a link</a>
          <a href="#">This is a link</a>
        </div>
      </div>
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
</script>