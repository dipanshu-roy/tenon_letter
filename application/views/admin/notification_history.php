<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
<style>
.show-amazing{
    background: #ffffff;
    border-color: #e3dede;
    font-size: 10px;
    color: #424040!important;
    padding: 1px 4px;
    text-align: left;
}

</style>
<div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#">Notification History</a>
                </li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                             <h6 class="card-title w-50">Notification History</h6>
                             <span class="w-50">
                             <a href="create-notification" class="float-right text-white btn-sm status_checks btn btn-success" style="height:20px;">Go Back</a>
                            </span>
                            </div>
                            <div style="overflow-x:auto;">
                            <table id="example1" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                                        <th>Message ID</th>
                                        <th>Notication Type</th>
                                        <th>Sent to</th>
                                        <th>Success</th>
                                        <th>Failed</th>
                                        <th>Acknowledge</th>
                                        <th>Sent Date</th>
                                        <th>Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                                        <tr>
                                            <td><?=$sr++;?></td>
                                            <td><?=$row->msg_id;?></td>
                                            <td><?php if($row->notication_type==1){ echo 'Branch Wise';}elseif($row->notication_type==2){ echo 'Customer Wise';}elseif($row->notication_type==3){ echo 'Company Wise';}elseif($row->notication_type==4){ echo 'Specific Employees';}elseif($row->notication_type==5){ echo 'Group Wise';}elseif($row->notication_type==6){ echo 'Title Wise';}elseif($row->notication_type==7){ echo 'Client visit allowed';}?></td>
                                            <td><?php if($row->tenon_connect==0){ echo 'Guard';}else{ echo 'Staff';}?></td>
                                            <td><?=$row->totat_sent;?></td>
                                            <td><?=$row->totat_failure;?></td>
                                            <td><?php $countid=$this->db->query("SELECT count(id) as id FROM `lt_sent_notification_history` WHERE msg_id='$row->msg_id' AND is_view=1")->row();
                                            echo $countid->id;
                                            ?></td>
                                            <td><?php $created_date=date_create($row->created_at); echo date_format($created_date,"d-m-Y H:i:s");?></td>
                                            <td style="white-space: nowrap;">
                                                <br/>
                                                <span style="border-bottom:1px solid #cdbebe;padding: 3px 0px"><b>Title</b>:<?=$row->msg_title;?><br/></span>
                                                <span style="border-bottom:1px solid #cdbebe;padding: 3px 0px"><b>Description</b>:<?=$row->msg_desc;?><br/></span>
                                                <b>Image</b>:
                                                <?php if(!empty($row->image)){?>
                                                    <a target="_blank" href="<?=base_url('assets/media/image/'.$row->image.'');?>" style="color:blue"> Click here</a>
                                                <?php }else{
                                                    echo 'N/A';
                                                }?><br/>
                                                <b><?php if($row->notication_type==1){ echo 'Branch';}elseif($row->notication_type==2){ echo 'Customer';}elseif($row->notication_type==3){ echo 'Company';}elseif($row->notication_type==4){ echo 'Employees';}elseif($row->notication_type==5){ echo 'Group';}elseif($row->notication_type==6){ echo 'Title';}elseif($row->notication_type==7){ echo 'Visit allowed';}?></b>:<?php if($row->notication_type==1 && !empty($row->branch_id)){
                                                    $branch=$this->db->query("SELECT name FROM `default_cio_locations` WHERE id=$row->branch_id")->row();
                                                    echo '<span  class="btn show-amazing">'.$branch->name.'</span>';
                                                }
                                                if($row->notication_type==2 && !empty($row->customer_id)){
                                                    $customer=$this->db->query("SELECT name FROM `customer_master` WHERE id in($row->customer_id) ORDER BY name DESC")->result();
                                                    if(!empty($customer)){
                                                        foreach($customer as $cust){
                                                            echo '<span  class="btn show-amazing">'.$cust->name.'</span></br>';
                                                        }
                                                    }
                                                }
                                                if($row->notication_type==3 && !empty($row->company_id)){
                                                    $company=$this->db->query("SELECT company_name FROM `lt_tbl_company` WHERE id='$row->company_id'")->row();
                                                    echo '<span  class="btn show-amazing">'.$company->company_name.'</span>';
                                                }
                                                if($row->notication_type==4 && !empty($row->employe_id)){
                                                    $variable=explode(",", $row->employe_id);
                                                    $code=implode("','", $variable);
                                                    $datas=$this->db->query("SELECT name,code FROM guard_master WHERE id in('$code')")->result();
                                                    if(!empty($datas)){
                                                        foreach($datas as $guar){
                                                            echo '<span  class="btn show-amazing">'.$guar->name.' <br/>( '.$guar->code.' )</span></br>';
                                                        }
                                                    }
                                                }if($row->notication_type==5 && !empty($row->group_id)){
                                                    $variable1=explode(",", $row->group_id);
                                                    $code1=implode("','", $variable1);
                                                    $datas1=$this->db->query("SELECT `group` FROM staff_master WHERE `group` in ('$code1') GROUP BY `group` ASC")->result();
                                                    if(!empty($datas1)){
                                                        foreach($datas1 as $gr){
                                                            echo '<span  class="btn show-amazing">'.$gr->group.'</span></br>';
                                                        }
                                                    }
                                                }if($row->notication_type==6 && !empty($row->title_id)){
                                                    $variable2=explode(",", $row->title_id);
                                                    $code2=implode("','", $variable2);
                                                    $datas2=$this->db->query("SELECT `title` FROM staff_master WHERE `title` in ('$code2') GROUP BY `title` ASC")->result();
                                                    if(!empty($datas2)){
                                                        foreach($datas2 as $ttl){
                                                            echo '<span  class="btn show-amazing">'.$ttl->title.'</span></br>';
                                                        }
                                                    }
                                                }if($row->notication_type==7){
                                                    if($row->visit_allowed_id==0){
                                                        echo '<span  class="btn show-amazing">No</span>';
                                                    }elseif($row->visit_allowed_id==1){
                                                        echo '<span  class="btn show-amazing">Yes</span>';
                                                    }
                                                }?><br/>
                                            </td>
                                            <td>
                                                <a class="text-white btn-sm status_checks btn btn-primary" href="<?=base_url('admin/export_in_csv/'.$row->msg_id.'/'.$row->tenon_connect);?>">Download Report &nbsp;<i class="fa fa-cloud-download" style="font-size:22px" aria-hidden="true"></i><a/>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php 
?>
