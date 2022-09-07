   <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#">Letter Master</a>
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
                            <h6 class="card-title">Create Letter</h6>
                            <form class="needs-validation" method="POST" novalidate="">
                                <div class="form-row">
                                      <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                    <div class="col-md-12 mb-3">
                                        <label for="validationCustom01">Letter name</label>
                                        <input type="text" class="form-control" name="letter_name" placeholder="Letter name" value="<?php echo !empty($updates->letter_name)? $updates->letter_name:''?>" required="">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="validationCustom02">Mode</label>
                                        <select class="form-control"  name="mode" required="">
                                            <option value="">Select</option>
                                            <option value="1" <?php echo @$updates->mode==1?'selected':''?>>Manually</option>
                                            <option value="2" <?php echo @$updates->mode==2?'selected':''?>>Auto</option>
                                        </select>
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
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                             <h6 class="card-title">Letters List</h6>
                            <table id="example1" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                                        <th>Letter Name</th>
                                        <th>Mode</th>
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
                                            <td><?=$row->letter_name;?></td>
                                            <td><?php echo $row->mode==1?'Manually':'Auto';?></td>
                                            <td><?php echo @$row->status==1?'Active':'Inactive';?></td>
                                            <td><?php $created_date=date_create($row->created_date); echo date_format($created_date,"d-m-Y H:i:s");?> </br> 
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
                                                    <a class="dropdown-item" href="<?=base_url('create-letter/'.$row->id.'');?>">Edit</a>
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