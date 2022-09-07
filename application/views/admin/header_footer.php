<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href=#>Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Header & Footer Master</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                 <h6 class="card-title">Create & Header Footer</h6>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                        <select class="select2-example form-control" name="company_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($company)){ foreach($company as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->company_id)){
                                                if($updates->company_id==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->company_name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('company_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Choose Header Image</label>
                                        <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                        <input type="file" name="header" class="form-control" required>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('role_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Choose Footer Image</label>
                                        <input type="file" name="footer" class="form-control" required>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('staff_id'); ?></div>
                                    </div>
                                </div>
                                <?/*?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select Letter</label>
                                        <select class="select2-example form-control" name="letter_mater_id" id="letter_mater_id" onchange="getTemplates()">
                                            <option value="">Select Letter</option>
                                             <?php if(!empty($letter)){ foreach($letter as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->letter_id)){
                                                if($updates->letter_id==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->letter_name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('letter_mater_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Letter Template</label>
                                        <select class="select2-example form-control" name="letter_template_id" id="letter_template_id">
                                            <option value="">Letter Template</option>
                                            
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('letter_template_id'); ?></div>
                                    </div>
                                </div>
                                <?*/?>
                            </div>
                            </span>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Header Footer List</h6>
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                       <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Company Name</th>
                        <th>Header Image</th>
                        <th>Footer Image</th>
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
                        <td><?=$row->company_name;?></td>
                        <td><a target="_blank" href="<?=base_url('assets/media/header_footer/'.$row->header.'');?>" class="d-flex align-items-center">
                            <img width="100" src="<?=base_url('assets/media/header_footer/'.$row->header.'');?>" class="rounded mr-3" alt="<?=$row->company_name;?>">
                            </a>
                        </td>
                        <td><a target="_blank" href="<?=base_url('assets/media/header_footer/'.$row->footer.'');?>" class="d-flex align-items-center">
                            <img width="100" src="<?=base_url('assets/media/header_footer/'.$row->footer.'');?>" class="rounded mr-3" alt="<?=$row->company_name;?>">
                            </a>
                        </td>
                        <td>
                            <input type="hidden" name="xid" value="<?=$row->status;?>" class="xid_<?=$row->id?>">
                            <button type="button" onclick="updateStatus(<?=$row->id?>)" id="<?=$row->id?>" class="text-white btn-sm status_checks btn <?php echo ($row->status == 1) ? "btn-danger" : "btn-success"; ?> " value="<?=$row->id;?>">
                                
                                <?php echo ($row->status == 1) ? "Inactive" : "Active"; ?>
                            </button>
                        </td> 
                          
                        <td><?php $created_date=date_create($row->created_date); echo date_format($created_date,"d-m-Y H:i:s"); echo $row->created_by;?> </br> 
                            <?php
                                if($row->created_by > 2){
                                    $created_by=$this->Allmodel->selectrow('system_users',array('id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }else{
                                    $created_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }
                            ?>
                        </td>
                        <td>
                            <?php if($row->update_date==''){ echo '';}else{ $updated_at=date_create($row->update_date); echo date_format($updated_at,"d-m-Y H:i:s");}?>
                            </br> 
                            <?php
                            if($row->updated_by !=''){
                                if($row->updated_by > 2){
                                    $updated_by=$this->Allmodel->selectrow('system_users',array('id'=>$row->updated_by)); echo 'by '.$updated_by->name; 
                                }else{
                                    $updated_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->updated_by)); echo 'by '.$updated_by->name; 
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
                                <a class="dropdown-item" href="<?=base_url('header-footer/'.$row->id.'');?>">Edit</a>
                              
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
        if(status ==0){
            var st =1;
        }else{
            var st =0;
        }
        $.ajax({
            type:"POST",
            url: "<?=base_url('ajax/header_footer_status');?>", 
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
</script>