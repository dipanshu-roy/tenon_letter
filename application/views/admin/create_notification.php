<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<!-- <script src="https://code.jquery.com/jquery-1.10.2.js"></script> -->
<style>
.show-amazing{
    background: #ffffff;
    border-color: #e3dede;
    font-size: 10px;
    color: #424040!important;
    padding: 1px 4px;
    text-align: left;
}
.custom-switch .custom-control-label::before {
    background-color: #aa66cc!important;
    color: white;
    border: #aa66cc solid 1px!important;
}
.custom-switch .custom-control-label::after{
    background-color: #ffffff;
}
</style>
<div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#">Send Notification</a>
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
                            <form class="needs-validation" method="POST" novalidate="" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group d-flex">
                                            <label style="margin-top: 4px;">Send To Guard &nbsp;</label>
                                            <div class="custom-control custom-switch custom-checkbox-secondary">
                                                <input type="checkbox" class="custom-control-input" name="tenon_connect" value="1" id="tenon_connect">
                                                <label class="custom-control-label" for="tenon_connect"> Send To Staff </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Select Notication Type</label>
                                        <select class="form-control" name="notication_type" id="notication_type" onchange="getTemplates()" required="">
                                            <option value="">Select </option>   
                                            <option value="1">Branch Wise</option>
                                            <option value="2">Customer Wise</option>
                                            <option value="3">Company Wise</option>
                                            <option value="4">Specific Employees</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label id="dataname">Select</label>
                                        <span class="selectbox_single">
                                            <select class="select2-example form-control" name="datatypes" id="datatypes">
                                            </select>
                                        </span>

                                        <span class="selectbox_multiple" style="display:none">
                                            <select class="select2-example form-control" name="datatypes1[]" id="datatypes1" multiple>
                                            </select>
                                        </span>

                                        <span class="textarea_employees" style="display:none">
                                            <textarea class="form-control" maxlength="225" name="datatypes2"></textarea>
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label>Message Title ( <span id="charsLeftTitle">60</span> )</label>
                                        <input type="text" maxlength="60" onkeyup="countCharTitle(this)" class="form-control" name="msg_title" placeholder="Message Title" required="">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Image</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label>Description ( <span id="charsLeft">225</span> )</label>
                                        <textarea class="form-control" onkeyup="countChar(this)" maxlength="225" name="description" required=""></textarea>
                                    </div>
                                    
                                </div>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                             <h6 class="card-title w-50">Pending Notification List</h6>
                             <span class="w-50">
                             <a href="notification-history" class="float-right text-white btn-sm status_checks btn btn-success" style="height:20px;">View Notification History</a>
                            </span>
                            </div>
                            <table id="example1" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                                        <th>Message Title</th>
                                        <th>Notication Type</th>
                                        <th>Name</th>
                                        <th>Sent On</th>
                                        <th>Status</th>
                                        <th>Created date & By</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                                        <tr>
                                            <td><?=$sr++;?></td>
                                            <td><?=$row->msg_title;?></td>
                                            <td><?php if($row->notication_type==1){ echo 'Branch Wise';}elseif($row->notication_type==2){ echo 'Customer Wise';}elseif($row->notication_type==3){ echo 'Company Wise';}elseif($row->notication_type==4){ echo 'Specific Employees';}elseif($row->notication_type==5){ echo 'Group Wise';}elseif($row->notication_type==6){ echo 'Title Wise';}elseif($row->notication_type==7){ echo 'Client visit allowed';}?></td>
                                            <td>
                                                <?php if($row->notication_type==1 && !empty($row->branch_id)){
                                                    $branchname=implode("','", explode(",", $row->branch_id));
                                                    $branch=$this->db->query("SELECT name FROM `default_cio_locations` WHERE id IN ('$branchname')")->result();
                                                    if(!empty($branch)){
                                                        foreach($branch as $brn){
                                                        echo '<span  class="btn show-amazing">'.$brn->name.'</span>';
                                                        }
                                                    }
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
                                                    $datas=$this->db->query("SELECT name,code FROM guard_master WHERE code in('$code')")->result();
                                                    if(!empty($datas)){
                                                        foreach($datas as $guar){
                                                            echo '<span  class="btn show-amazing">'.$guar->name.' <br/>( '.$guar->code.' )</span></br>';
                                                        }
                                                    }
                                                }if($row->notication_type==5 && !empty($row->group_id)){
                                                    $variable1=explode(",", $row->group_id);
                                                    $code1=implode("','", $variable1);
                                                    $datas1=$this->db->query("SELECT DISTINCT `group` FROM staff_master WHERE `group` in ('$code1') ORDER BY `group` ASC")->result();
                                                    if(!empty($datas1)){
                                                        foreach($datas1 as $gr){
                                                            echo '<span  class="btn show-amazing">'.$gr->group.'</span></br>';
                                                        }
                                                    }
                                                }if($row->notication_type==6 && !empty($row->title_id)){
                                                    $variable2=explode(",", $row->title_id);
                                                    $code2=implode("','", $variable2);
                                                    $datas2=$this->db->query("SELECT DISTINCT `title` FROM staff_master WHERE `title` in ('$code2') ORDER BY `title` ASC")->result();
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
                                                }?>
                                            </td>
                                            <td><?php if($row->tenon_connect==0){ echo 'Guard';}else{ echo 'Staff';}?></td>
                                            <td>
                                                <input type="hidden" name="xid" value="<?=$row->status;?>" class="xid_<?=$row->id?>">
                                                <button type="button" onclick="updateStatus(<?=$row->id?>)" id="<?=$row->id?>" class="text-white btn-sm status_checks btn <?php echo ($row->status == 1) ? "btn-danger" : "btn-success"; ?> " value="<?=$row->id;?>">
                                                    <?php echo ($row->status == 1) ? "Inactive" : "Active"; ?>
                                                </button>
                                            </td> 
                                            <td><?php $created_date=date_create($row->created_at); echo date_format($created_date,"d-m-Y H:i:s");?> </br> 
                                                <?php
                                                if($row->user_id > 2){
                                                    $users=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->user_id)); echo 'by '.$users->name; 
                                                }else{
                                                    $users=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->user_id)); echo 'by '.$users->name; 
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>Action 
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?=base_url('admin/delete_notification/'.$row->id.'');?>">Delete</a>
                                                </div>
                                                </div>
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
<script>
$(".selectbox_multiple").hide();
 function getTemplates(){
    var notication_type = $('#notication_type').val();
    $("#datatypes").empty('');
    $("#datatypes1").empty('');
    $.ajax({
        type:"POST",
        url: "<?=base_url('Ajax/notication_type');?>", 
        data: {"notication_id":notication_type}, 
        dataType: "json",
        success: function(data) { 
            if(data.status==200){
                var datas = data.alldata;
                if(data.multiple==1){
                    $(".textarea_employees").hide();
                    $(".selectbox_single").hide();
                    $(".selectbox_multiple").show();
                    for(var i=0; i<datas.length; i++) {
                        $("#datatypes1").append('<option value="'+datas[i].id+'">'+datas[i].name+'</otion>');
                    }
                }else{
                    $(".textarea_employees").hide();
                    $(".selectbox_multiple").hide();
                    $(".selectbox_single").show();
                    for(var i=0; i<datas.length; i++) {
                        $("#datatypes").append('<option value="'+datas[i].id+'">'+datas[i].name+'</otion>');
                    }
                }
                $("#dataname").html(data.names);
            }
            if(data.status==202){
                $(".selectbox_single").hide();
                $(".selectbox_multiple").hide();
                $(".textarea_employees").show();
                $("#dataname").html(data.names);
            }
        } 
    });
}
function updateStatus($item){
      var status =$(".xid_"+$item).val();
    var msg = (status==0)? 'Inactive' : 'active'; 
    if(confirm("Are you sure to "+ msg)){
        var current_element = document.getElementById($item);
        if(status ==0){
            var st =1;
        }else{
            var st =0;
        }
        $.ajax({
            type:"POST",
            url: "<?=base_url('ajax/notification_status');?>", 
            data: {"id":$item,"status":st}, 
            success: function(data) { 
                if(status ==1){
                    $(".xid_"+$item).val(0)
                    current_element.classList.remove('btn-danger');
                    current_element.classList.add('btn-success');
                    $("#"+$item).html('Active');
                }else{  
                    $(".xid_"+$item).val(1)
                    current_element.classList.remove('btn-success');
                    current_element.classList.add('btn-danger');
                    $("#"+$item).text('Inactive');
                }
            }  
        });
    }
    
}
function countChar(val) {
  var len = val.value.length;
  if (len >= 225) {
    val.value = val.value.substring(0, 225);
  } else {
    $('#charsLeft').text(225 - len);
  }
};
function countCharTitle(val) {
  var len = val.value.length;
  if (len >= 60) {
    val.value = val.value.substring(0, 60);
  } else {
    $('#charsLeftTitle').text(60 - len);
  }
};
</script>
<script>
    $(document).ready(function(){
        $("#tenon_connect").change(function(){
            if($(this).is(':checked')){
                $('#notication_type').html('');
                selectValues = {
                    4:"Specific Employees",
                    3:"Company Wise",
                    5:"Group Wise",
                    6:"Title Wise",
                    7:"Client visit allowed",
                };
                $('#notication_type').append('<option value="">Select</option>');
                for (key in selectValues) {
                    $('#notication_type').append('<option value="' + key + '">' + selectValues[key] + '</option>');
                }
            }else{
                $('#notication_type').html('');
                selectValues = {
                    1:"Branch Wise",
                    2:"Customer Wise",
                    3:"Company Wise",
                    4:"Specific Employees",
                };
                $('#notication_type').append('<option value="">Select</option>');
                for (key in selectValues) {
                    $('#notication_type').append('<option value="' + key + '">' + selectValues[key] + '</option>');
                }
            }
        });
    });
$(document).ready(function() {
    $('#example').DataTable({
        "lengthMenu": [[50,100, 200, -1], [50,100, 200, "All"]],
        "dom": 'Bfrtip',
        scrollY: 500,
        scrollX: true, 
        scroller: true,
        buttons: ['csv', 'pageLength']
    } );
} );
</script>
