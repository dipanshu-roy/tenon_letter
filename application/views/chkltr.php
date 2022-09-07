<!DOCTYPE html>
<html>

<head>
    <title>Check Letter</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        @media screen {
            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 400;
                src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 700;
                src: local('Lato Bold'), local('Lato-Bold'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 400;
                src: local('Lato Italic'), local('Lato-Italic'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 700;
                src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
            }
            
            
           
        }

        /* CLIENT-SPECIFIC STYLES */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media screen and (max-width:600px) {
            h1 {
                font-size: 32px !important;
                line-height: 32px !important;
                padding-left: 17px;
            }
            
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
      <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  
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
            width: 190px;
            min-width: 190px;
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
    </style>
</head>

<body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
    <!-- HIDDEN PREHEADER TEXT -->
   
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- LOGO -->
        <tr>
            <td bgcolor="#7C2173" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;"> </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#7C2173" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; ">
                            <img class="image_tenon" src="https://www.tenonworld.com/wp-content/uploads/2020/05/tenon-fm-small.png" width="150" height="120" style="display: block; border: 0px; float:left;" >
                            <img class="image_peregrine" src="https://www.peregrine-security.com/wp-content/uploads/2018/07/peregrine-logo.png" width="150" height="120" style="display: block; border: 0px; float:right;padding-top:10px;" >
                          
                            <h1 style="font-size: 48px; font-weight: 400; margin: 2;font-size: 48px; font-weight: 400;  letter-spacing: 4px; line-height: 48px;">Welcome!</h1> 
                            
                             <?php if(!empty($this->session->flashdata('success'))){?>
                              <div class="alert alert-success"><?=$this->session->flashdata('success');?></div>
                            <?php }elseif(!empty($this->session->flashdata('error'))){ ?>
                              <div class="alert alert-danger"><?=$this->session->flashdata('error');?></div>
                            <?php }?>
                            <div class="alert alert-success" id="alert"></div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <tr>

            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">

                <?php

                // $this->session->unset_userdata('letter_name');

                if(!empty($this->session->userdata('letter_name'))){?>

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;" >

                     <tr>

                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">

                            Letter Name

                        </td>

                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">

                            Action

                        </td>

                    </tr>

                    <?php

                    $all_letters = $this->session->userdata('letter_name'); 
                    if(!empty($all_letters)){
                    foreach($all_letters as $letter){?>

                    <tr>

                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">

                            <p style="margin: 0;"><?=$letter->letter_name;?></p>

                        </td>

                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">

                            <p style="margin: 0;"><a target="_blank" href="<?=base_url().'apointment-letter/'.$this->Allmodel->encrypt($letter->id,KEY);?>/<?=$letter->letter_id;?>/<?=$letter->letter_template_id;?>" style="color: #FFA73B;">View Letter</a></p>

                        </td>

                    </tr>

                    <?php }  } 
                    $letter_other = $this->session->userdata('letter_other'); 
                    if(!empty($letter_other)){
                    foreach($letter_other as $other){?>
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;"><?=$other->letter_title;?></p>
                        </td>
                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                        <p style="margin: 0;"><a target="_blank" href="<?=base_url('assets/OtherLetters/');?><?=$other->letter;?>" style="color: #FFA73B;">View Letter</a></p>
                        </td>
                    </tr>
                    <?php }  } ?>
                </table>

                 

                <?php }else{?>

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;"  id="mobile_div">

                    <tr>

                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">

                            <p style="margin: 0;">For Check your letter please enter your mobile number.</p>

                        </td>

                    </tr>

                    <tr>

                        <td bgcolor="#ffffff" align="left">

                            <div class="form-group" style="width: 50%; margin: auto;">

                                <label for="usr">Enter Mobile Number:</label>

                                <input type="text" class="form-control" name="my_number" id="my_number" placeholder="Mobile Number">

                            </div>

                        </td>

                    </tr> 

                    <tr>

                        <td bgcolor="#ffffff" align="left">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr>

                                    <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">

                                        <table border="0" cellspacing="0" cellpadding="0">

                                            <tr>

                                                <td align="center" style="border-radius: 3px;" bgcolor="#FFA73B">

                                                    <button class="btn btn-lg btn-primary" onclick="cheak_number()">Send OTP</button>

                                                </td>

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr> 

                    

                </table>

                

                

                <form id="otpsection" method="POST">

                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;"  id="otp_div">

                    <tr>

                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">

                            <p style="margin: 0;">Please Check your mobile & enter your OTP.</p>

                        </td>

                    </tr>

                    <tr>

                        <td bgcolor="#ffffff" align="left">

                            <div class="form-group" style="width: 50%; margin: auto;">

                                <label for="usr">Enter OTP:</label>

                                 <input type="text"  maxlength="4" id="input_otp" name="input_otp"/>

                            </div>

                        </td>

                    </tr> 

                    <tr>

                        <td bgcolor="#ffffff" align="left">

                            <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                <tr>

                                    <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">

                                        <table border="0" cellspacing="0" cellpadding="0">

                                            <tr>

                                                <td align="center" style="border-radius: 3px;" bgcolor="#FFA73B">

                                                    <button class="btn btn-lg btn-success"  type="submit"  onclick="match_otp()">Submit</button>

                                                </td>

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr> 

                </table>

                </form>

                <?php } ?>

            </td>

        </tr>
       
        
    </table>
</body>

</html>


<script>
$("#otp_div").hide();
$("#alert").hide();
function cheak_number(){
  var number=$('#my_number').val();
  if(number==''){
    $('#alert').html('Please Enter Mobile Number');
  }else{
      
    $.ajax({
        type: "POST",
        url: "<?=base_url();?>ajax/cheak_number",
        async: false,
        data: {
          my_number:$("#my_number").val(),
        },
        success: function(response){
          $("#alert").show();
          if(response.status=='200'){
              $("#mobile_div").hide();
              $("#otp_div").show();
              $('#alert').html(response.msg);
              $('#otpsection').append('<input type="hidden" value="'+response.employee_id+'" id="employee_id" name="employee_id"> <input type="hidden" value="'+response.mobile+'" id="mobile" name="mobile">');
          }else{
              $('#alert').html(response.msg);
          }
        }
    });
  }
}
function match_otp(){
    $.ajax({
    type: "POST",
    url: "<?=base_url();?>ajax/match_chkltr_otp",
    data: jQuery("#otpsection").serialize(),
    async: false,
    success: function (msg) {
      if (msg == 'sucess') {
        $("#alert").show();
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
