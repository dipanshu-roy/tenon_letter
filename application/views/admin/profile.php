   <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
   <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#">User Profile</a>
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
                            <h6 class="card-title">Update Profile</h6>
                            <form action="" method="POST" enctype="multipart/form-data">
                                <?php foreach($result as $updates){
                                ?>
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Role</label>
                                            <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                            <input type="text" name="name" value="<?php echo !empty($updates->role_id)? $updates->roll_name:'Admin'?>" class="form-control" readonly>
                                            <div class="invalid-feedback d-block"> <?php echo form_error('role_id'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Officer Name</label>
                                            <input type="text" name="name" value="<?php echo !empty($updates->name)? $updates->name:''?>" class="form-control">
                                            <div class="invalid-feedback d-block"> <?php echo form_error('staff_id'); ?></div>
                                        </div>
                                    </div>
                                    <?php if(!empty($updates->site_id)){?>
                                    <div class="col-md-4 mb-3">
                                        <label for="validationCustom03">Branch</label>
                                        <input type="text" name="name" value="<?php echo !empty($updates->site_id)? $updates->site_id:''?>" class="form-control" readonly>
                                    </div>
                                    <?php } ?>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="hidden" name="password" class="form-control" value="<?php echo !empty($updates->password)? $updates->password:''?>">
                                            <input type="email" name="email" class="form-control" value="<?php echo !empty($updates->email)? $updates->email:set_value('email');?>" placeholder="Enter email">
                                            <div class="invalid-feedback d-block"> <?php echo form_error('email'); ?></div>
                                        </div>          
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Mobile Number</label>
                                            <input type="text" name="mobile" class="form-control" value="<?php echo !empty($updates->mobile)? $updates->mobile:set_value('phone_number');?>" placeholder="Enter Mobile Number" pattern="[0-9]*" maxlength="10" minlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" >
                                            <div class="invalid-feedback d-block"> <?php echo form_error('mobile'); ?></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" class="form-control" value="<?php echo !empty($updates->address)? $updates->address:set_value('address');?>" placeholder="Enter Address" > 
                                            <div class="invalid-feedback d-block"> <?php echo form_error('address'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6 mb-3">
                                        <label for="">Password</label>
                                        <input type="password" name="password" class="form-control" value="<?php echo !empty($updates->password)? $this->Allmodel->decrypt($updates->password,KEY):''?>">
                                    </div>
                                </div>
                                <?php } ?>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
       <script>
        //  Form Validation
        window.addEventListener('load', function () {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function (form) {
                form.addEventListener('submit', function (event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    </script>