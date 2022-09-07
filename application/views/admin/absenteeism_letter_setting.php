<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href=#>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Absenteeism Letter Setting</li>
        </ol>
    </nav>
       <a onclick="show()" class="btn btn-primary text-white" id="show" style="margin-left: 40%;">Create Absenteeism Letter setting</a>
       <a onclick="hide()" class="btn btn-primary text-white" id="hide" style="margin-left: 40%;">Absenteeism Letter setting list</a>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card" id="addform">
            <div class="card-body">
                 <h6 class="card-title">Create Absenteeism Letter Setting</h6>
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
                                        <label>First Warning Letter</label>
                                        <select class="select2-example form-control" name="first_warning_letter_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($letter_templates)){ foreach($letter_templates as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->first_warning_letter_id )){
                                                if($updates->first_warning_letter_id ==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->letter_name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('first_warning_letter_id '); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>First Warning Days</label>
                                        <input type="text" name="first_warning_days" class="form-control"  value="<?php echo !empty($updates->first_warning_days)? $updates->first_warning_days:''?>" placeholder="First Warning Days" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                        <div class="invalid-feedback d-block"> <?php echo form_error('first_warning_days'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Second Warning Letter</label>
                                        <select class="select2-example form-control" name="second_warning_letter_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($letter_templates)){ foreach($letter_templates as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->second_warning_letter_id)){
                                                if($updates->second_warning_letter_id==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->letter_name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('second_warning_letter_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Second Warning Days</label>
                                        <input type="text" name="second_warning_days" class="form-control"  value="<?php echo !empty($updates->second_warning_days)? $updates->second_warning_days:''?>" placeholder="Second Warning Days" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                        <div class="invalid-feedback d-block"> <?php echo form_error('second_warning_days'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Termination Letter Letter</label>
                                        <select class="select2-example form-control" name="termination_letter_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($letter_templates)){ foreach($letter_templates as $rows){?>
                                            <option value="<?=$rows->id;?>" <?php if(!empty($updates->termination_letter_id)){
                                                if($updates->termination_letter_id==$rows->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$rows->letter_name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('termination_letter_id '); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Termination Days</label>
                                        <input type="text" name="termination_days" class="form-control"  value="<?php echo !empty($updates->termination_days)? $updates->termination_days:''?>" placeholder="Second Warning Days" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');">
                                        <div class="invalid-feedback d-block"> <?php echo form_error('termination_days'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="validationCustom03">Status</label>
                                    <select class="form-control"  name="status" required="">
                                        <option value="">Select</option>
                                        <option value="1" <?php echo @$updates->status==1?'selected':''?>>Active</option>
                                        <option value="2" <?php echo @$updates->status==2?'selected':''?>>Inactive</option>
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
                 <h6 class="card-title">Absenteeism Letter Setting List</h6>
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                       <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Company_name</th>
                        <th>First Warning Letter</th>
                        <th>First Warning Day</th>
                        <th>Second Warning Letter</th>
                        <th>Second Warning Day</th>
                        <th>Termination Day</th>
                        <th>Termination Letter</th>
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
                        <td><?=$row->template_name1;?></td>   
                        <td><?=$row->first_warning_days;?></td>   
                        <td><?=$row->template_name2;?></td>   
                        <td><?=$row->second_warning_days;?></td>   
                        <td><?=$row->template_name3;?></td>   
                        <td><?=$row->termination_days;?></td>   
                        <td><?php echo $row->status==1?'Active':'Inactive';?></td>
                        <td>
                            <?php $created_at=date_create($row->created_date); echo date_format($created_at,"d-M-Y H:i:s");?></br>
                            <?php
                                if($row->created_by > 2){
                                    $created_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }else{
                                    $created_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }
                            ?>
                        </td>
                        <td>
                            <?php if($row->updated_at=='0000-00-00 00:00:00'){ echo '';}else{ $updated_at=date_create($row->updated_at); echo date_format($updated_at,"d-M-Y H:i:s");}?>
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
                                <a class="dropdown-item" href="<?=base_url('absenteeism-letter-setting/'.$row->id.'');?>">Edit</a>
                               
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
    var id = "<?php echo !empty($updates->id)? $updates->id:''?>";
    if(id ==''){
        $("#addform").hide();
        $("#hide").hide();
        function show(){
            $("#addform").show();
            $("#show").hide();
            $("#hide").show();
        }
        function hide(){
            $("#addform").hide();
            $("#hide").hide();
            $("#show").show();
        }
    }else{
        $("#show").hide();
        $("#hide").hide();
    }
    
</script>