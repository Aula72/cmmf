
<nav id="nav" class="green">CMMF
<ul id="slide-out" class="side-nav green">
    <li><div class="user-view">
      <div class="background">
        <img src="/assets/img/account.png" width="100%">
      </div>
      <a href="#!user"><img class="circle" src="/assets/img/account.png"></a>
      <!-- <a href="#!name"><span class="black-text name" id="">User</span></a> -->
      <a href="#!email"><span class="black-text email" id="email"></span></a>
      </div>
    </li>
    <li><a href="/"><i class="material-icons">home</i>Dashboard</a></li>
    <li><a href="/weeks"><i class="material-icons">date_range</i>Weeks</a></li>
    <li><a href="/groups"><i class="material-icons">diversity_2</i>Groups</a></li>
    <!-- <li><a href="/group-members"><i class="material-icons">groups</i>Group Members</a></li> -->
    <li><a href="/ledgers"><i class="material-icons">table_view</i>Ledgers</a></li>
    <li><a href="/loans"><i class="material-icons">attach_money</i>Loans</a></li>
    <li><div class="divider"></div></li>
    <li><a href="/admin"><i class="material-icons">keyboard</i>Admins</a></li>
    <li><a class="waves-effect" href="#!"><i class="material-icons">account_circle</i>My Account</a></li>
    <li><a href="/logout"><i class="material-icons">logout</i>Log Me Out</a></li>
    
  </ul>

  <a href="#" data-activates="slide-out" class="button-collapse"><i class="material-icons">menu</i></a>
  </nav>

  <script >
    $(".button-collapse").sideNav();
    $(document).ready(function() {
        $('select').material_select();
        
    });
    $('#email').text(localStorage.getItem('mail'))
    

  </script>

<script>
    var ses = localStorage.getItem("token");
    let em = localStorage.getItem("mail")
    let st = window.location.pathname;
    if(ses=='' || ses==null){
        $('#nav').css({display:"none"});    
        
        // if()    
    }
    if(em=='' || em==null && st != '/login'){
          window.location = "/login";
    }
    // if((st!='/login' || st !="/otp") && (ses=='' || ses==null)){
    //   window.location = '/login';
    // }

    
</script>