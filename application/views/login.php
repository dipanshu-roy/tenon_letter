<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?=APP_NAME;?> - Login</title>

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
<h1 class="form-wrapper" style="margin: 9px auto;font-size: 38px; font-weight: 600;padding: 13px;color: #7c1073;">Site HR Portal</h1>
<div class="form-wrapper" style="text-align: left;width: 616px;height: 450px;">
    
    <div class="col-md-12 row">
        <div class="col-md-6"><img class="logo" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>"></div>
        <div class="col-md-6"><img class="logo" src="<?=base_url('assets/'.TENON_LOGO.'');?>" alt="<?=APP_NAME;?>" style="width: 78%;float: right;"></div>
        <div class="col-md-12"><h5 style="font-size: 38px; font-weight: 600;">Sign in</h5></div>
    </div>
    
    <!-- form -->
    <form action="" method="POST" style="margin-top: 36px;">
        <div class="form-group">
            <input type="text" name="email" class="form-control" value="<?=set_value('email');?>" placeholder="Enter Your Email" style="margin-bottom: 0.2rem;">
            <div class="invalid-feedback d-block"> <?php echo form_error('email'); ?></div>
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" style="margin-bottom: 0.2rem;">
            <div class="invalid-feedback d-block"> <?php echo form_error('password'); ?></div>
        </div>
        <div class="form-group d-flex justify-content-between">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" checked="" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember me</label>
            </div>
            <a href="<?=base_url('recovery-password');?>">Reset password</a>
        </div>
        <button class="btn btn-primary btn-block">Sign in</button>
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
