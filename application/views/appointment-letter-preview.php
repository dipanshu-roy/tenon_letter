<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Appointment Letter</title>

    <link rel="shortcut icon" href="<?=base_url('assets/'.FAV.'');?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/bundle.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>css/app.min.css" type="text/css">
</head>
<body class="form-membership">
<!-- begin::preloader-->
<div class="preloader">
    <div class="preloader-icon"></div>
</div>
<!-- end::preloader -->
<div class="form-wrapper" style="text-align: left;width: 1082px;">
    <div id="logo" class="text-center">
        <img class="logo" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>">
        <img class="logo-dark" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>">
    </div>
    <?php print_r($message);?>
    
    <!-- form -->
    <form action="" method="POST">
        <button class="btn btn-primary btn-block">Accept</button>
        <hr>
    </form>
</div>
<script src="<?=base_url('assets/');?>vendors/bundle.js"></script>
<script src="<?=base_url('assets/');?>js/app.min.js"></script>
<?php if(!empty($this->session->flashdata('success'))){ ?>
<script>
setTimeout(function () {
    toastr.options = {
        timeOut: 2000,
        progressBar: true,
        showMethod: "slideDown",
        hideMethod: "slideUp",
        showDuration: 200,
        hideDuration: 200,
        positionClass: "toast-top-center"
    };
    toastr.success('<?=$this->session->flashdata('success');?>');
    $('.theme-switcher').removeClass('open');
}, 500);
</script>
<?php }elseif(!empty($this->session->flashdata('error'))){ ?>
<script>
setTimeout(function () {
    toastr.options = {
        timeOut: 2000,
        progressBar: true,
        showMethod: "slideDown",
        hideMethod: "slideUp",
        showDuration: 200,
        hideDuration: 200,
        positionClass: "toast-top-center"
    };
    toastr.error('<?=$this->session->flashdata('error');?>');
    $('.theme-switcher').removeClass('open');
}, 500);
</script>
<?php } ?>
</body>
</html>
