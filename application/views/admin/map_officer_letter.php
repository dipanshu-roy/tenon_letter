<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href=#>Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Map Letter Templates With Branch</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                  <h6 class="card-title">Map Letter Templates With Branch</h6>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select class="select2-example form-control" name="branch_id" id="branch_id"onchange="getofficer()">
                                            <option value="">Select</option>
                                            <?php if(!empty($default_cio)){ foreach($default_cio as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->branch_id)){
                                                if($updates->branch_id==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('branch_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Letter Type</label>
                                        <select class="select2-example form-control" name="letter_mater_id" id="letter_mater_id" onchange="getTemplates()">
                                            <option value="">Select</option>
                                            <?php if(!empty($letter_master)){ foreach($letter_master as $letter_master_row){?>
                                            <option value="<?=$letter_master_row->id;?>" <?php if(!empty($updates->letter_mater_id)){
                                                if($updates->letter_mater_id==$letter_master_row->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$letter_master_row->letter_name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('letter_mater_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Letter Template</label>
                                        <select class="select2-example form-control" name="letter_template_id" id="letter_template_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($up_letter_template)){ foreach($up_letter_template as $ltr_tem){?>
                                            <option value="<?=$ltr_tem->id;?>"
                                                <?php if($updates->letter_template_id==$ltr_tem->id){
                                                    echo 'selected';} ?>><?=$ltr_tem->template_name;?> ( <?=$ltr_tem->lang_code;?> - <?=$ltr_tem->name;?> )</option>
                                            <?php } }?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('letter_template_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Authorised officer</label>
                                        <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                        <select class="select2-example form-control staff_master" name="officer_id" >
                                            <option value="">Select</option>
                                            <?php foreach($staff as $staff_data){ ?>                                                
                                                <option value="<?=$staff_data->officer_id?>"
                                                <?php if($updates->officer_id==$staff_data->officer_id){
                                                    echo 'selected';}?>><?=$staff_data->name?></option>
                                                <?php }?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('officer_id'); ?></div>
                                    </div>
                                </div>
                                
                                
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                  <h6 class="card-title">Map Letter Templates With Branch List</h6>
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                       <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Officer Name</th>
                        <th>Branch Name</th>
                        <th>Letter Name</th>
                        <th>Template Name</th>
                        <th>Status</th>
                        <th>Created date & By</th>
                        <th>Updated date & By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                    <tr>
                        <td><?=$sr++;?></td>
                        <td><?=$row->officer_name;?></td>      
                        <td><?=$row->branch_name;?></td>   
                        <td><?=$row->letter_name;?></td>   
                        <td><?=$row->template_name;?> <br/>( <?=$row->lang_code;?> <?=$row->lang_name;?> )</td>   
                        <td>
                            <input type="hidden" name="xid" value="<?=$row->status;?>" class="xid_<?=$row->id?>">
                            <button type="button" onclick="updateStatus(<?=$row->id?>)" id="<?=$row->id?>" class="text-white btn-sm status_checks btn <?php echo ($row->status == 1) ? "btn-danger" : "btn-success"; ?> " value="<?=$row->id;?>">
                                
                                <?php echo ($row->status == 1) ? "Inactive" : "Active"; ?>
                            </button>
                        </td>   
                        <td><?php $created_date=date_create($row->created_date); echo date_format($created_date,"d-m-Y H:i:s");?> </br> 
                            <?php
                                if($row->created_by > 2){
                                    $created_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->created_by)); echo 'by '.@$created_by->name; 
                                }else{
                                    $created_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->created_by)); echo 'by '.@$created_by->name; 
                                }
                            ?>
                        </td>
                        
                        <td>
                            <?php if($row->updated_at=='0000-00-00 00:00:00'){
                                 echo '';}else{ 
                                     $updated_at=date_create($row->updated_at); 
                                     echo date_format($updated_at,"d-m-Y H:i:s");
                                     }?>
                            </br> 
                            <?php
                            if($row->updated_by !=''){
                                if($row->updated_by > 2){
                                    $updated_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->updated_by)); echo 'by '.@$updated_by->name; 
                                }else{
                                    $updated_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->updated_by)); echo 'by '.@$updated_by->name; 
                                }
                            }
                            ?>
                        </td>
                        <td>
                            <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>Action 
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?=base_url('map-officer-letter/'.$row->id.'');?>">Edit</a>
                            
                            </div>
                            </div>
                        </td>
                    </tr>
                    <?php } } ?>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
<script>

function updateStatus($item){
      var status =$(".xid_"+$item).val();
    var msg = (status==0)? 'Inactive' : 'active'; 
    if(confirm("Are you sure to "+ msg)){
        var current_element = document.getElementById($item);
        $.ajax({
            type:"POST",
            url: "<?=base_url('ajax/map_officer_letter_status');?>", 
            data: {"id":$item,"status":status}, 
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
 function getTemplates(){
    var current_element = $('#letter_mater_id').val();
    $("#letter_template_id").empty('');
    $.ajax({
        type:"POST",
        url: "<?=base_url('ajax/getTemplates');?>", 
        data: {"id":current_element}, 
        success: function(data) { 
            $("#letter_template_id").html(data);
        } 
    });
}
 function getofficer(){
    var current_element = $('#branch_id').val();
    $("#officer_id").empty('');
    $.ajax({
        type:"POST",
        url: "<?=base_url('ajax/getOfficer');?>", 
        data: {"id":current_element}, 
        success: function(data) { 
            $("#officer_id").html(data);
        } 
    });
}
</script>