<?php 
$mail = $_GET["email"];
?>

<div class="valign-wrapper">
<div class="progress">
      <div class="indeterminate"></div>
</div>
<div class="valign-wrapper">

<script>
    let mail = "<?php echo $mail; ?>"
    localStorage.setItem("mail", mail)
    setTimeout(() => {        
        window.location = "/otp"
    }, xtime);
    
</script>