<?php 
  $c = isset($_SERVER['HTTPS'])?'https':'http';
  define('URL', $c.'://'.$_SERVER['HTTP_HOST']);
  // $base_url = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>CMMF</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="<?php echo URL; ?>/assets/img/favicon.png" rel="icon">
  <link href="<?php echo URL; ?>/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="<?php echo URL; ?>/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo URL; ?>/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo URL; ?>/assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="<?php echo URL; ?>/assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="<?php echo URL; ?>/assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="<?php echo URL; ?>/assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="<?php echo URL; ?>/assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="<?php echo URL; ?>/assets/css/style.css" rel="stylesheet">
  <link href="<?php echo URL; ?>/assets/css/mycss.css" rel="stylesheet">
<script src="<?php echo URL; ?>/assets2/js/jquery.js"></script>
  <!-- =======================================================
  * Template Name: NiceAdmin - v2.4.1
  * Template URL: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>