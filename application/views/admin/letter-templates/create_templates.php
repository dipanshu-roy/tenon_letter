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
                            <h6 class="card-title">Create Letter Template</h6>
                           
                            <form class="needs-validation" method="POST" novalidate="">
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Letter Template Name</label>
                                        <input type="text" class="form-control" name="template_name" placeholder="Letter Template Name" value="" required="">
                                    </div>
                                    
                                    <?php /* ?><div class="col-md-12 mb-3">
                                        <label for="validationCustom02">Select Branch</label>
                                        <select class="form-control"  name="branch_id" required="">
                                            <option value="">Select Letter</option>
                                            <?php foreach($get_branch as $branch){?>
                                            <option value="<?=$branch->id?>"><?=$branch->name?></option>
                                            <?php } ?>
                                        </select>
                                    </div> <?php */ ?>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom02">Letter</label>
                                        <select class="form-control"  name="letter_master_id" required="">
                                            <option value="">Select</option>
                                            <?php foreach($get_letters as $letter){?>
                                            <option value="<?=$letter->id?>"><?=$letter->letter_name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom02">Language</label>
                                        <select class="form-control"  name="language" required="">
                                            <option value="">Select</option>
                                            <?php foreach($get_language as $lang){?>
                                            <option value="<?=$lang->id?>"><?=$lang->name?> (<?=$lang->lang_code?>)</option>
                                            <?php } ?>
                                        </select>
                                    </div>									
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom03">Status</label>
                                        <select class="form-control"  name="status" required="">
                                            <option value="">Select</option>
                                            <option value="1">Active</option>
                                            <option value="0">InActive</option>
                                        </select>
                                    </div>
                                     <div class="col-md-6 mb-3">
                                        <label for="validationCustom03">Variable list</label>
                                        <select  name="veriables[]" required="" class="select2-example" multiple>
                                            <?php foreach($get_letter_variable_list as $veriables){?>
                                            <option value="<?=$veriables->id?>"><?=$veriables->column_name?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <span class="text-danger">Note: If you want to insert variable name in the template, use {{ }} around it. Ex. {{employee_name}}</span>
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom03">Template Content</label>
                                        <textarea type="hiden" name="description"></textarea>
                                    </div>
                                   
                                </div>
                                <button class="btn btn-primary" type="submit">Submit</button>
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
    

