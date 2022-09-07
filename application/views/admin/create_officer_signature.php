<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href=#>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Officer with Signature</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Create Officer With Signature</h6>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select class="select2-example form-control" name="branch_id" id="branch_id" onchange="test()">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Officer Name</label>
                                        <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                        <select class="select2-example form-control staff_master" name="officer_id" id="officer_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($staff)){ foreach($staff as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->officer_id)){
                                                if($updates->officer_id==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('officer_id'); ?></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Upload Signature Image</label>
                                        <input type="file" name="signature_image" class="form-control" accept="image/png, image/jpg, image/jpeg"  required>
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
                <h6 class="card-title">Officer With Signature List</h6>
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                     <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Name</th>
                        <th>Branch Name</th>
                        <th>Signature</th>
                        <th>Created date & By</th>
                        <th>Updated date & By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                    <tr>
                        <td><?=$sr++;?></td>
                        <td><?=$row->name;?></td>
                        <td><?=$row->branch_name;?></td>   
                        <td><a target="_blank" href="<?=base_url('assets/media/signature_image/'.$row->signature_image.'');?>" class="d-flex align-items-center">
                            <img width="100" src="<?=base_url('assets/media/signature_image/'.$row->signature_image.'');?>" class="rounded mr-3" alt="<?=$row->name;?>">
                            </a>
                        </td>   
                        <td><?php $created_date=date_create($row->created_date); echo date_format($created_date,"d-m-Y H:i:s"); echo $row->created_by;?> </br> 
                            <?php
                                if($row->created_by > 2){
                                    $created_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->created_by)); echo 'by '.$created_by->name; 
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
                                    $updated_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->updated_by)); echo 'by '.$updated_by->name; 
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
                                <a class="dropdown-item" href="<?=base_url('create-officer-signature/'.$row->id.'');?>">Edit</a>
                              
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
function test(){
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