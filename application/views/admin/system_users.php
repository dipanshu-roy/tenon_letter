<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href=#>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">System Users</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Create System Users</h6>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Role</label>
                                        <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                        <select class="select2-example form-control" name="role_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($roles)){ foreach($roles as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->role_id)){
                                                if($updates->role_id==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('role_id'); ?></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Officer Name</label>
                                        <select class="select2-example form-control staff_master" name="staff_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($staff)){ foreach($staff as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->staff_id)){
                                                if($updates->staff_id==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->name;?> (<?=$rows->code;?>)</option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('staff_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="validationCustom03">Branch</label>
                                    <select  name="branch_ids[]" required="" class="select2-example form-control" multiple>
                                        <?php foreach($get_branch as $get_branch_name){
                                                $selectd_variable = explode(',', $updates->branch_id);
                                                if(in_array($get_branch_name->id,$selectd_variable)){
                                            ?>
                                            <option value="<?=$get_branch_name->id?>" selected><?=$get_branch_name->name?></option>
                                            <?php }else{ ?>
                                            <option value="<?=$get_branch_name->id?>" ><?=$get_branch_name->name?></option>
                                            <?php }  } ?>
                                    </select>
                                </div>
                            </div>
                            <span class="get_staff_master">
                                <?php if(!empty($updates)){ ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="hidden" name="name" class="form-control" value="<?php echo !empty($updates->name)? $updates->name:''?>">
                                                <input type="hidden" name="password" class="form-control" value="<?php echo !empty($updates->password)? $updates->password:''?>">
                                                <input type="email" name="email" class="form-control" value="<?php echo !empty($updates->email)? $updates->email:set_value('email');?>" placeholder="Enter email" readonly>
                                                <div class="invalid-feedback d-block"> <?php echo form_error('email'); ?></div>
                                            </div>          
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <input type="text" name="mobile" class="form-control" value="<?php echo !empty($updates->mobile)? $updates->mobile:set_value('mobile');?>" placeholder="Enter Mobile Number" pattern="[0-9]*" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" readonly>
                                                <div class="invalid-feedback d-block"> <?php echo form_error('mobile'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="address" class="form-control" value="<?php echo !empty($updates->address)? $updates->address:set_value('address');?>" placeholder="Enter Address" readonly> 
                                                <div class="invalid-feedback d-block"> <?php echo form_error('address'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </span>
                            <div class="form-row">
                                <div class="col-md-6 mb-3">
                                    <label for="">Status</label>
                                    <select class="form-control"  name="status" required="">
                                        <option value="">Select</option>
                                        <option value="1" <?php echo @$updates->status==1?'selected':''?>>Active</option>
                                        <option value="0" <?php echo @$updates->status==0?'selected':''?>>Inactive</option>
                                    </select>
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
                <h6 class="card-title">System Users List</h6>
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Role</th>
                        <th>Staff Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Branch Name</th>
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
                        <td><?=$row->roll_name;?></td>
                        <td><?=$row->name;?> (<?=@$row->staff_code;?>)</td>
                        <td><?=$row->email;?></td>
                        <td><?=$row->mobile;?></td>  
                        <td><?=ucwords($row->address);?></td>  
                        <td>
                            <?php
                            $branchs = $this->Allmodel->selectrowsByIn('default_cio_locations',$row->branch_id);
                            $b_name =[];
                             foreach($branchs as $branch_name){
                                array_push($b_name,$branch_name->ouinstname);
                             }
                             echo implode(",",$b_name);
                            ?>
                        </td>  
                        <td><?php echo $row->status==1?'Active':'Inactive';?></td>
                        <td><?php $created_at=date_create($row->created_at); echo date_format($created_at,"d-M-Y H:i:s");?></br>
                         <?php
                                if($row->created_by > 2){
                                    
                                    $created_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }else{
                                    $created_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }
                            ?>
                        </td>
                        <td>
                            <?php if($row->updated_at==''){ echo '';}else{ $updated_at=date_create($row->updated_at); echo date_format($updated_at,"d-M-Y H:i:s");}?></br>
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
                                <a class="dropdown-item" href="<?=base_url('system-users/'.$row->id.'');?>">Edit</a>
                             
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
$(document).ready(function(){
    $('.staff_master').change(function(){ 
        $.ajax({ 
            type  : 'POST',
            url   : "<?=base_url('ajax/get_staff_master');?>",
            data  : { id: this.value }, 
            cache : false, 
            success: function(response) { 
                $('.get_staff_master').html(); $('.get_staff_master').html(response); 
            } 
        }); 
    });
}); 
</script>

