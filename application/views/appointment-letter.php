<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Appointment Letter</title>

    <link rel="shortcut icon" href="<?=base_url('assets/'.FAV.'');?>"/>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/bundle.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>css/app.min.css" type="text/css">
	<style>
        #input_otp {
            padding-left: 15px;
            letter-spacing: 42px;
            border: 0;
            background-image: linear-gradient(to left, black 70%, rgba(255, 255, 255, 0) 0%);
            background-position: bottom;
            background-size: 50px 1px;
            background-repeat: repeat-x;
            background-position-x: 35px;
            width: 220px;
            min-width: 220px;
            }

            #divInner{
            left: 0;
            position: sticky;
            }

            #divOuter{
            width: 190px; 
            overflow: hidden;
            margin: auto;
        }
        .modal{
            pointer-events: none!important;
        }
        .modal-dialog{
            pointer-events: all!important;
        }
		@media print {
          #printPageButton {
            display: none;
          }
        }
    </style>
</head>
<body class="form-membership">
<!-- begin::preloader-->
<div class="preloader">
    <div class="preloader-icon"></div>
</div>
<!-- end::preloader -->
<div class="form-wrapper" style="text-align: left;width: 1082px;">
    <div id="logo" class="text-center">
		
         <img class="logo" src="<?=base_url('assets/media/header_footer/'.$header_footer->header.'');?>" alt="<?php echo $header_footer->header; ?>" style="width: 100%;">
        <?/*?><img class="logo" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>">
        <img class="logo-dark" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>"><?*/?>
    </div>
    <hr>
	<p><b>Ref:</b> <?php echo $employee_detailsss->letter_serial_number;?></p>
    <?php print_r($message);?>
    <hr>
    <div id="logo" class="text-center">
         <img class="logo" src="<?=base_url('assets/media/header_footer/'.$header_footer->footer.'');?>" alt="<?php echo $header_footer->footer; ?>" style="width: 98%;">
        <?/*?><img class="logo" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>">
        <img class="logo-dark" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>"><?*/?>
    </div>
    <!-- form -->
    <?php if(!empty($employee_detailsss->confirmation_status)){ if($employee_detailsss->confirmation_status==1){ ?>
          <button class="btn btn-primary btn-block" id="printPageButton" onclick="window.print()">Download</button>
    <?php } }else{ ?>
      <div class="modal-content" id="otp_div" style="width: 448px;margin: auto;">
          <div class="modal-header">
            <h4 class="modal-title">Enter Your OTP</h4>
          </div>
          <div class="modal-body">
            <p class="text-success">An otp has been sent to your registered mobile number</p>
            <form id="otpsection" method="POST">
            <div id="divOuter">
                <div id="divInner">
                    <input type="hidden" value="<?=$this->uri->segment(2);?>" id="employee_id" name="employee_id">
                    <input type="text" maxlength="4" id="input_otp" name="input_otp"/>
                    <br/><br/>
                    <button type="submit" class="btn btn-primary btn-block actions_button" onclick="match_otp()">Submit</button>
                </div>
            </div>
            </form>
          </div>
        </div>
        <input type="hidden" value="<?=$this->uri->segment(2);?>" id="otp">
        <button class="btn btn-primary btn-block" onclick="send_otp()" data-toggle="modal" data-target="#myModal">Accept</button>
    <?php }?>
</div>
</div>

<?/*?><div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Enter Your OTP</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p class="text-success">An otp has been sent to your registered mobile number</p>
        <form id="otpsection" method="POST">
        <div id="divOuter">
            <div id="divInner">
                <input type="hidden" value="<?=$this->uri->segment(2);?>" id="employee_id" name="employee_id">
                <input type="text" maxlength="4" id="input_otp" name="input_otp"/>
                <br/><br/>
                <button type="submit" class="btn btn-primary btn-block actions_button" onclick="match_otp()">Submit</button>
            </div>
        </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?*/?>
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
<script>
    $("#otp_div").hide();
function send_otp(){
  $.ajax({
      type: "POST",
      url: "<?=base_url('ajax/send_otp');?>",
      async: false,
      data: {
        otp:$("#otp").val(),
      },
      success: function(response){
        $('.send_otp').html(response);
        $("#otp_div").show();
      }
  });
}
function match_otp(){
    $.ajax({
    type: "POST",
    url: "<?=base_url('ajax/match_otp');?>",
    data: jQuery("#otpsection").serialize(),
    async: false,
    success: function (msg) {
      if (msg == 'sucess') {
        setTimeout(function () {
          spinner.hide();
        }, 500);
      }
    }
  });
}
$(document).ready(function() {
$('#input_otp').keyup(function() {
    let empty = false;
    $('#input_otp').each(function() {
      empty = $(this).val().length == 4;
    });
    if (empty)
        $('.actions_button').attr('disabled', false);
    else
        $('.actions_button').attr('disabled', 'disabled');
  });
});
$('.actions_button').attr('disabled', 'disabled');
</script>
</body>
</html>
