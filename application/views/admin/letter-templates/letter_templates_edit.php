   <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#">Letter Templates</a>
                </li>
            </ol>
            
        </nav>
         <a href="<?=base_url('create-templates/letter-template-list');?>" class="btn btn-primary text-white" style="margin-left: 53%;">Letter Templates List</a>
    </div>
    
     <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Update Letter Template</h6>
                           
                            <form  action="<?=base_url('create-templates/letter-template-edit/'.$updates->id)?>" class="needs-validation" method="POST" novalidate="">
                                  <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Letter Template Name</label>
                                        <input type="text" class="form-control" name="template_name" placeholder="Letter Template Name" value="<?php echo !empty($updates->template_name)? $updates->template_name:''?>" required="">
                                    </div>
                                   <?php /*?> 
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom02">Select Branch</label>
                                        <select class="form-control"  name="branch_id" required="">
                                            <option value="">Select Letter</option>
                                            <?php foreach($get_branch as $branch){?>
                                            <option value="<?=$branch->id?>" <?php if($updates->branch_id == $branch->id){ echo "selected"; }?>><?=$branch->name?></option>
                                            <?php } ?>
                                        </select>
                                    </div><?php */?>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom02">Letter</label>
                                        <select class="form-control"  name="letter_master_id" required="">
                                            <option value="">Select</option>
                                            <?php foreach($get_letters as $letter){?>
                                            <option value="<?=$letter->id?>" <?php if($updates->letter_master_id == $letter->id){ echo "selected"; }?>><?=$letter->letter_name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom02">Language</label>
                                        <select class="form-control"  name="language" required="">
                                            <option value="">Select</option>
                                            <?php foreach($get_language as $lang){?>
                                            <option value="<?=$lang->id?>" <?php if($updates->language == $lang->id){ echo "selected"; }?>><?=$lang->name?> (<?=$lang->lang_code?>)</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom03">Status</label>
                                        <select class="form-control"  name="status" required="">
                                            <option value="">Select</option>
                                            <option value="1" <?php echo @$updates->status==1?'selected':''?>>Active</option>
                                            <option value="0" <?php echo @$updates->status==0?'selected':''?>>Inactive</option>
                                        </select>
                                    </div>
                                     <div class="col-md-6 mb-3">
                                        <label for="validationCustom03">Variable list</label>
                                        <select  name="veriables[]" required="" class="select2-example" multiple>
                                            <?php foreach($get_letter_variable_list as $veriables){
                                                $selectd_variable = explode(',', $updates->veriables);
                                                if(in_array($veriables->id,$selectd_variable)){
                                            ?>
                                            <option value="<?=$veriables->id?>" selected><?=$veriables->column_name?></option>
                                            <?php }else{ ?>
                                            <option value="<?=$veriables->id?>" ><?=$veriables->column_name?></option>
                                            <?php }  } ?>
                                        </select>
                                    </div>
                                      <span class="text-danger">Note: If you want to insert variable name in the template, use {{ }} around it. Ex. {{employee_name}}</span>
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom03">Template Content</label>
                                        <textarea type="hiden" name="description" required=""><?=$updates->content;?></textarea>
                                    </div>
                                   
                                </div>
                                <button class="btn btn-primary" type="submit">Update</button>
                            </form>
                        </div>
                    </div>
                    
                   
                </div>
            </div>

        </div>
    </div>
    
    
    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>
    <!-- Style -->
    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
    
    <!-- Javascript -->
    <script src="<?=base_url('assets/');?>vendors/select2/js/select2.min.js"></script>

    <script>
        CKEDITOR.replace( 'description' );
    </script>
    

