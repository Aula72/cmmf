<?php 
$t = explode("/",$_SERVER['REQUEST_URI']);
// echo "###################    ".$t[1]."     ###################################\n";
function actives($rt){
    if($t[1]===$rt){
        echo " collapsed";
    }else{
        echo "";
    }
}

// echo "#####################      ".actives("/weeks")."     ###########################\n";
// echo "#####################      ".strcmp('weeks',$t[1])."     ##########################\n";
// echo "#####################      ".json_encode($t)."        ##########################\n";
?>

<div id="nav">
<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

<div class="d-flex align-items-center justify-content-between">
  <a href="/" class="logo d-flex align-items-center">
    <img src="<?php echo URL; ?>/assets/img/logo.png" alt="">
    <span class="d-none d-lg-block"><?php echo APP_NAME; ?></span>
  </a>
  <i class="bi bi-list toggle-sidebar-btn"></i>
</div><!-- End Logo -->

<div class="search-bar">
  <form class="search-form d-flex align-items-center" method="POST" action="#">
    <input type="text" name="query" placeholder="Search" title="Enter search keyword">
    <button type="submit" title="Search"><i class="bi bi-search"></i></button>
  </form>
</div><!-- End Search Bar -->

<nav class="header-nav ms-auto">
  <ul class="d-flex align-items-center">

    <li class="nav-item d-block d-lg-none">
      <a class="nav-link nav-icon search-bar-toggle " href="#">
        <i class="bi bi-search"></i>
      </a>
    </li><!-- End Search Icon-->
    
    <!-- End Messages Nav -->

    <li class="nav-item dropdown pe-3">

      <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
        <img src="<?php echo URL; ?>/assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
        <span class="d-none d-md-block dropdown-toggle ps-2" id="log_name">K. Anderson</span>
      </a><!-- End Profile Iamge Icon -->

      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
        <li class="dropdown-header">
          <h6 id="loged_name"></h6>
          <span id="log_mail"></span>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="/admin/5">
            <i class="bi bi-person"></i>
            <span>My Profile</span>
          </a>
        </li>
        <li>
          <hr class="dropdown-divider">
        </li>

        
        <li>
          <hr class="dropdown-divider">
        </li>

        
        <li>
          <hr class="dropdown-divider">
        </li>

        <li>
          <a class="dropdown-item d-flex align-items-center" href="/logout">
            <i class="bi bi-arrow-left"></i>
            <span>Logout</span>
          </a>
        </li>

      </ul><!-- End Profile Dropdown Items -->
    </li><!-- End Profile Nav -->

  </ul>
</nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <li class="nav-item">
    <a class="nav-link <?php actives('');?>" href="/">
      <i class="bi bi-grid"></i>
      <span>Dashboard</span>
    </a>
  </li><!-- End Dashboard Nav -->
  <li class="nav-item">
    <a class="nav-link <?php actives('weeks'); ?>" href="/weeks">
      <i class="bi bi-calendar-date-fill"></i>
      <span>Weeks</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php actives('groups');?>" href="/groups">
      <i class="bi bi-people"></i>
      <span>Groups</span>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?php actives('loans');?>" href="/loans">
      <i class="bi bi-currency-exchange"></i>
      <span>Loans</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link <?php actives('ledger');?>" href="/ledgers">
      <i class="bi bi-file-earmark-spreadsheet"></i>
      <span>Ledgers</span>
    </a>
  </li>
  
  <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#charts-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-bar-chart"></i><span>Reports</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="charts-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="/transaction-reports">
              <i class="bi bi-circle"></i><span>Transactions</span>
            </a>
          </li>
          <li>
            <a href="/loan-reports">
              <i class="bi bi-circle"></i><span>Loans</span>
            </a>
          </li>
          <li>
            <a href="/user-reports">
              <i class="bi bi-circle"></i><span>User</span>
            </a>
          </li>
        </ul>
      </li>
  
      <li class="nav-item">
    <a class="nav-link <?php actives('logs');?>" href="/logs">
      <i class="bi bi-list-task"></i>
      <span>Activity Logs</span>
    </a>
  </li>
  
  <li class="nav-item">
    <a class="nav-link <?php actives('my-profile');?>" href="/admin/5">
      <i class="bi bi-person"></i>
      <span>My Profile</span>
    </a>
  </li><!-- End Profile Page Nav -->

  <li class="nav-item">
    <a class="nav-link <?php actives('admin');?>" href="/admin">
      <i class="bi bi-people"></i>
      <span>Users</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link collapsed" href="/logout">
      <i class="bi bi-arrow-left"></i>
      <span>Logout</span>
    </a>
  </li>

  
</ul>

</aside><!-- End Sidebar-->
</div>

<script>
    
</script>

<main id="main" class="main">
    <div id="alerting"></div>
    <div class="pagetitle" id="p_title">
      <h1 id="h10">Dashboard</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active" id="h12">Dashboard</li>
        </ol>
      </nav>
    </div>

    <div id="print-title" style="display:none; text-align:center; padding:10px;">
      <h3 style="text-align:center; color: green; ">CMMF</h3>
      <h2>P.O Box <?php echo POX; ?></h2>
      <hr style="color:red;">
    </div>

