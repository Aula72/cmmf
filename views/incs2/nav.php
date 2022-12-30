
<nav id="nav" class="green">CMMF
<ul id="slide-out" class="side-nav green ">
    <li><div class="user-view">
      <div class="background">
        <img src="/assets/img/account.png" width="100%">
      </div>
      <a href="#!user"><img class="circle" src="/assets/img/user.png"></a>
      <!-- <a href="#!name"><span class="black-text name" id="">User</span></a> -->
      <a href="#!email"><span class="white-text email" id="email"></span></a>
      </div>
    </li>
    <li><a href="/" class="white-text"><i class="material-icons white-text">home</i>Dashboard</a></li>
    <li><a href="/groups" class="white-text"><i class="material-icons white-text">diversity_2</i>Groups</a></li>
    <li><a href="/weeks" class="white-text"><i class="material-icons white-text">date_range</i>Weeks</a></li>
    
    <!-- <li><a href="/group-members"><i class="material-icons">groups</i>Group Members</a></li> -->
    <li><a href="/ledgers" class="white-text"><i class="material-icons white-text">table_view</i>Ledgers</a></li>
    <li><a href="/loans" class="white-text"><i class="material-icons white-text">attach_money</i>Loans</a></li>
    <li><a href="/reports" class="white-text"><i class="material-icons white-text">book</i>Reports</a></li>
    <li><div class="divider"></div></li>
    <li><a href="/admin" class="white-text"><i class="material-icons white-text">keyboard</i>Admins</a></li>
    <!-- <li><a class="waves-effect" href="#!"><i class="material-icons">account_circle</i>My Account</a></li> -->
    <li><a href="/logout" class="white-text"><i class="material-icons white-text">logout</i>Log Me Out</a></li>
    
  </ul>

  <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
  </nav>

 
  <!-- <script >
    $(".button-collapse").sideNav();
    $(document).ready(function() {
        $('select').material_select();
        
    });
    $('#email').text(localStorage.getItem('mail'))
    

  </script> -->

<script>
    var ses = localStorage.getItem("token");
    let em = localStorage.getItem("mail")
    let st = window.location.pathname;
    if(ses=='' || ses==null){
        $('#nav').css({display:"none"});
        
    }
    if(em=='' || em==null && st != '/login'){
          window.location = "/login";
    }
    
</script>

