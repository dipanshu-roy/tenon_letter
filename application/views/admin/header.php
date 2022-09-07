<?php if(!empty($this->session->userdata('s_admin')) || !empty($this->session->userdata('system_user'))){ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo !empty(APP_NAME) ? APP_NAME.' || '.ucfirst($this->uri->segment(1)):'';?></title>
    <link rel="shortcut icon" href="<?=base_url('assets/'.FAV.'');?>"/>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/bundle.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/slick/slick.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/slick/slick-theme.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/datepicker/daterangepicker.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/dataTable/datatables.min.css" type="text/css">
    <link rel="stylesheet" href="<?=base_url('assets/');?>css/app.min.css" type="text/css">
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <style type="text/css">

.apexcharts-canvas {
  position: relative;
  user-select: none;
  /* cannot give overflow: hidden as it will crop tooltips which overflow outside chart area */
}

/* scrollbar is not visible by default for legend, hence forcing the visibility */
.apexcharts-canvas ::-webkit-scrollbar {
  -webkit-appearance: none;
  width: 6px;
}

i.fas.fa-chevron-right {
    color: #7c1073;
    margin: 0px 3px;
}
i.fas.fa-tachometer-alt {
    position: relative;
    color: #7c1073;
    padding-right: 4px;

}
.w3-purple, .w3-hover-purple:hover {
    color: #fff!important;
    background-color: #7c1073!important;
    margin-left: 25px;
    margin-bottom: 20px;
    font-size: 15px;
    font-weight: 500;
    width: 230px;
    font-family: "Inter", sans-serif;
}


</style>
</head>
<body  class="">
<div class="">
    <!--preloader-->
    <div class="preloader-icon"></div>
</div>
<div class="sidebar-group">
    <div class="sidebar" id="user-menu">
        <div class="py-4 text-center" data-backround-image="<?=base_url('assets/');?>media/image/image1.jpg">
            <figure class="avatar avatar-lg mb-3 border-0">
                <img src="<?=base_url('assets/');?>media/image/user/user.png" class="rounded-circle" alt="image">
            </figure>
            <h5 class="d-flex align-items-center justify-content-center" style="text-transform: capitalize;"><?php echo !empty($usersdata->name) ? $usersdata->name:'';?></h5>
         
        </div>
        <div class="card mb-0 card-body shadow-none">
            <div class="mb-4">
                <div class="list-group list-group-flush">
                    <a href="<?=base_url('user/profile');?>" class="list-group-item p-l-r-0">Profile</a>
                    <a href="<?=base_url('logout');?>" class="list-group-item p-l-r-0 text-danger">Sign Out!</a>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="layout-wrapper">
    <div class="header d-print-none">
        <div class="header-left">
            <div class="navigation-toggler">
                <a href="#" data-action="navigation-toggler">
                    <i data-feather="menu"></i>
                </a>
            </div>
            <div class="header-logo">
                <a href=#>
                    <img class="logo" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>">
                    <img class="logo-light" src="<?=base_url('assets/'.LOGO.'');?>" alt="<?=APP_NAME;?>">
                </a>
            </div>
            <div class="header-logo">
                <a href=#>
                    <img class="logo" src="<?=base_url('assets/'.TENON_LOGO.'');?>" alt="<?=APP_NAME;?>" style="width: 78%;margin-left: 73px;">
                    <img class="logo-light" src="<?=base_url('assets/'.TENON_LOGO.'');?>" alt="<?=APP_NAME;?>" style="width: 78%;margin-left: 73px;">
                </a>
            </div>
        </div>
        <div class="header-body">
            <div class="header-body-left" style="width: 895px;">
                <div class="page-title">
                    <h1 class="text-center" style="font-size: 38px; font-weight: 600;padding: 13px;color: #7c1073;">Site HR Portal</h1>
                </div>
            </div>
            <div class="header-body-right">
                <ul class="navbar-nav">

                    <!-- begin::user menu -->
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link" title="User menu" data-sidebar-target="#user-menu">
                            <span class="mr-2 d-sm-inline d-none" style="text-transform: capitalize;"><?php echo !empty($usersdata->name) ? $usersdata->name:'';?></span>
                            <figure class="avatar avatar-sm">
                                <img src="<?=base_url('assets/');?>media/image/user/user.png" class="rounded-circle"
                                     alt="avatar">
                            </figure>
                        </a>
                    </li>
                    <!-- end::user menu -->

                </ul>

                <!-- begin::mobile header toggler -->
                <ul class="navbar-nav d-flex align-items-center">
                    <li class="nav-item header-toggler">
                        <a href="#" class="nav-link">
                            <i data-feather="arrow-down"></i>
                        </a>
                    </li>
                </ul>
                <!-- end::mobile header toggler -->
            </div>
        </div>
    </div>
    <!-- end::header -->

    <div class="content-wrapper">
        
        <div class="navigation">
            <div class="navigation-menu-body">
                <div class="navigation-menu-group">
                    <div>
                    <ul>
                            <li class="navigation-divider d-flex align-items-center">
                            </li>
                            
                            <li><a <?php if($this->uri->segment(1)=='admin-dashboard' || $this->uri->segment(2)=='profile'){ echo 'class="active"';}?> href="<?=base_url('admin-dashboard')?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			    <?php if($this->session->userdata('s_admin')->id==3){ ?>
                <li><a <?php if($this->uri->segment(1)=='create-refine-notification'){ echo 'class="active"';}elseif($this->uri->segment(1)=='refine-notification-history'){ echo 'class="active"';}?> href="<?=base_url('create-refine-notification')?>"><i class="fas fa-chevron-right"></i> Refyne Notification Template</a></li>
                                
				<li><a <?php if($this->uri->segment(1)=='refyne-delivery-reports'){ echo 'class="active"';}elseif($this->uri->segment(1)=='refyne-delivery-reports'){ echo 'class="active"';}?> href="<?=base_url('refyne-delivery-reports')?>"><i class="fas fa-chevron-right"></i> Refyne Delivery Reports</a></li>
			    <?php }else{ ?>
                            <?php if($permissions!=1){?>
                                <?php foreach($permissions as $urlname){ ?>
                                    <li><a <?php if($urlname->key==$this->uri->segment(1)){ echo 'class="active"';}?> href="<?=base_url($urlname->key)?>"><i class="fas fa-chevron-right"></i> <?=$urlname->menu_name;?></a></li>
                                <?php  } ?>
                            <?php }elseif($permissions==1){ ?>
                                <li><a <?php if($this->uri->segment(1)=='create-roles'){ echo 'class="active"';}?> href="<?=base_url('create-roles')?>"><i class="fas fa-chevron-right"></i> Create roles</a></li>
                                <li><a <?php if($this->uri->segment(1)=='system-users'){ echo 'class="active"';}?> href="<?=base_url('system-users')?>"><i class="fas fa-chevron-right"></i> System users</a></li>
                                <li><a <?php if($this->uri->segment(1)=='create-letter'){ echo 'class="active"';}?> href="<?=base_url('create-letter')?>"><i class="fas fa-chevron-right"></i> Create Letter Master</a></li>
                                <li><a <?php if($this->uri->segment(1)=='create-templates'){ echo 'class="active"';}?> href="<?=base_url('create-templates/letter-template-list')?>"><i class="fas fa-chevron-right"></i> Create Letter Templates</a></li>
                                <li><a <?php if($this->uri->segment(1)=='create-officer-signature'){ echo 'class="active"';}?> href="<?=base_url('create-officer-signature')?>"><i class="fas fa-chevron-right"></i> Create officer with Signature</a></li>
                                <li><a <?php if($this->uri->segment(1)=='map-officer-letter'){ echo 'class="active"';}?> href="<?=base_url('map-officer-letter')?>"><i class="fas fa-chevron-right"></i> Map letter templates with branch</a></li>
                                <li><a <?php if($this->uri->segment(1)=='absenteeism-letter-setting'){ echo 'class="active"';}?> href="<?=base_url('absenteeism-letter-setting')?>"><i class="fas fa-chevron-right"></i> Absenteeism Letter Setting</a></li>
                                <li><a <?php if($this->uri->segment(1)=='approval-to-termination'){ echo 'class="active"';}?> href="<?=base_url('approval-to-termination')?>"><i class="fas fa-chevron-right"></i> Approval to Termination</a></li>
                                <li><a <?php if($this->uri->segment(1)=='upload-cards'){ echo 'class="active"';}?> href="<?=base_url('upload-cards')?>"><i class="fas fa-chevron-right"></i> Upload Cards (ESIC & GHI)</a></li>
                                <li><a <?php if($this->uri->segment(1)=='letter-reports-with-filters'){ echo 'class="active"';}?> href="<?=base_url('letter-reports-with-filters')?>"><i class="fas fa-chevron-right"></i> Letters Acceptance Status</a></li>
                                <li><a <?php if($this->uri->segment(1)=='issue-warning-letters'){ echo 'class="active"';}?> href="<?=base_url('issue-warning-letters')?>"><i class="fas fa-chevron-right"></i> Issue Warning Letters</a></li>
                                <li><a <?php if($this->uri->segment(1)=='other-letters'){ echo 'class="active"';}?> href="<?=base_url('other-letters')?>"><i class="fas fa-chevron-right"></i> Issue Other Letters</a></li>
                                <li><a <?php if($this->uri->segment(1)=='employee-exit'){ echo 'class="active"';}?> href="<?=base_url('employee-exit')?>"><i class="fas fa-chevron-right"></i> Employee Exit</a></li>
                                <li><a <?php if($this->uri->segment(1)=='header-footer'){ echo 'class="active"';}?> href="<?=base_url('header-footer')?>"><i class="fas fa-chevron-right"></i> Header & Footer Master</a></li>
								<li><a <?php if($this->uri->segment(1)=='pending-letter-reports'){ echo 'class="active"';}?> href="<?=base_url('pending-letter-reports')?>"><i class="fas fa-chevron-right"></i> Pending Letter Reports</a></li>
                                <li><a <?php if($this->uri->segment(1)=='create-notification'){ echo 'class="active"';}?> href="<?=base_url('create-notification')?>"><i class="fas fa-chevron-right"></i> Send Notification</a></li>
                                <li><a <?php if($this->uri->segment(1)=='notification-history'){ echo 'class="active"';}elseif($this->uri->segment(1)=='notification-history'){ echo 'class="active"';}?> href="<?=base_url('notification-history')?>"><i class="fas fa-chevron-right"></i> Notification History</a></li>
                                <li><a <?php if($this->uri->segment(1)=='create-refine-notification'){ echo 'class="active"';}elseif($this->uri->segment(1)=='refine-notification-history'){ echo 'class="active"';}?> href="<?=base_url('create-refine-notification')?>"><i class="fas fa-chevron-right"></i> Refyne Notification Template</a></li>
                                <li><a <?php if($this->uri->segment(1)=='refyne-delivery-reports'){ echo 'class="active"';}elseif($this->uri->segment(1)=='refyne-delivery-reports'){ echo 'class="active"';}?> href="<?=base_url('refyne-delivery-reports')?>"><i class="fas fa-chevron-right"></i> Refyne Delivery Reports</a></li>
                                <li>
                                    <a <?php if($this->uri->segment(1)=='advance-salary-report'){ echo 'class="active"';}?> <?php if($this->uri->segment(1)=='advance-salary-withdrawal'){ echo 'class="active"';}?> <?php if($this->uri->segment(1)=='advance-salary-apilog'){ echo 'class="active"';}?>><i class="fas fa-chevron-right"></i> Employee Loan</a>
                                    <ul>
                                        <li><a <?php if($this->uri->segment(1)=='advance-salary-report'){ echo 'class="active"';}?> href="<?=base_url('advance-salary-report')?>"><i class="fas fa-chevron-right" style="padding-right: 10px;"></i> Employee Loan Transaction Report</a></li>
    								    <li><a <?php if($this->uri->segment(1)=='advance-salary-withdrawal'){ echo 'class="active"';}?> href="<?=base_url('advance-salary-withdrawal')?>"><i class="fas fa-chevron-right" style="padding-right: 10px;"></i> Employee Loan Withdrawal Report</a></li>
    								    <li><a <?php if($this->uri->segment(1)=='advance-salary-apilog'){ echo 'class="active"';}?> href="<?=base_url('advance-salary-apilog')?>"><i class="fas fa-chevron-right" style="padding-right: 10px;"></i> Employee Loan Api Log</a></li>
                                    </ul>
                                </li>
								
                            <?php } }?>
                        </ul>
                           <button class="w3-button w3-purple">Logout</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end::navigation -->

        <div class="content-body">

            <div class="content">
<!-- END: Sidebar Group -->
<?php } else { echo "<script>alert('LogIn First');document.location='".base_url()."'</script>"; } ?>