<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">

<div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Letters Acceptance Status</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#">Letter Reports - Transaction</a>
                </li>
                
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group row">
                            <div class="col-sm-3">
                                <label class="">Branch</label>
                                <input type="hidden" name="id" value="">
                                <?php if(!empty($this->session->userdata('s_admin'))){
                                    $req = "";
                                } if(!empty($this->session->userdata('system_user'))){
                                    $req = "required";
                                } ?>


                                <select class="select2-example form-control" name="branch_id" id="branch_id" <?=$req?>>
                                    <option value="">Select</option>
                                    <?php if(!empty($this->session->userdata('s_admin'))){ ?>
                                        <?php if(!empty($default_cio)){ foreach($default_cio as $rows){?>
                                        <option value="<?=$rows->id;?>" <?php if(!empty($updates->branch_id)){
                                            if($updates->branch_id==$rows->id){
                                                echo 'selected';
                                            }
                                        } ?>><?=$rows->name;?></option>
                                        <?php } } ?>
                                    <?php } ?>
                                    
                                    <?php 
                                        if(!empty($this->session->userdata('system_user'))){
                                            	$users=$this->session->userdata('system_user');
                                        $branch_ids = explode(',',$users->branch_id);
                                            if(!empty($default_cio)){ 
                                                foreach($default_cio as $rows){ 
                                                    if(in_array($rows->id,$branch_ids)){
                                        ?>
                                                        <option value="<?=$rows->id;?>" <?php if(!empty($updates->branch_id)){
                                                            if($updates->branch_id==$rows->id){
                                                                echo 'selected';
                                                            }
                                                        } ?>><?=$rows->name;?></option>
                                        <?php       }
                                                } 
                                            } 
                                        }?>
                                    
                                </select>
                            </div> 
                            <div class="col-md-3">
                                <label for="">Letter Type</label>
                                <select class="form-control"  name="letter_type">
                                    <option value="">Select</option>
                                    <?php foreach($get_letters as $letter){?>
                                    <option value="<?=$letter->id?>" <?php if($updates->letter_type == $letter->id){ echo "selected"; }?>><?=$letter->letter_name?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="">Date when Generated</label>
                                <div class="col-md-6" style="display: flex; position: absolute;padding-left: 0px;">
                                  <span class="mt-2">From</span> <input type="date" class="form-control" name="from_date"style="width: 74%;margin-left: 6px;">
                                </div>
                                <div class="col-md-6" style="display: flex; position: absolute; margin-left: 14pc;">
                                  <span class="mt-2">To</span> <input type="date" class="form-control" name="to_date" style="width: 74%;margin-left: 6px;">
                                </div>
                            </div>
                            <div class="col-md-3  mt-4">
                                <label for="">SMS Status</label>
                                <select class="form-control" name="sms_status">
                                    <option value="">Select</option>
                                    <option value="0" <?php  if(!empty($_POST['sms_status'])){ if($_POST['sms_status']=='0'){ echo 'selected';} } ?>>Sent</option>
                                    <option value="1" <?php if(!empty($_POST['sms_status'])){ if($_POST['sms_status']=='1'){ echo 'selected';} } ?>>Delivered</option>
                                </select>
                            </div>
                            <div class="col-md-3  mt-4">
                                <label for="">Acceptance Status</label>
                                <select class="form-control" name="confirm_status">
                                    <option value="">Select</option>
                                    <option value="1" <?php  if(!empty($_POST['confirm_status'])){ if($_POST['confirm_status']=='1'){ echo 'selected';} } ?>>Accepted</option>
                                    <option value="2" <?php if(!empty($_POST['confirm_status'])){ if($_POST['confirm_status']=='2'){ echo 'selected';} } ?>>Not Accepted</option>
                                </select>
                            </div>
                            <div class="col-md-3  mt-4">
                                <label for="">Employee Code/Name</label>
                                <input type="text" name="emp_code" class="form-control" value="<?php echo !empty($_POST['emp_code'])? $_POST['emp_code']:'';?>" placeholder="Search by Employee Code/Name">
                            </div>
                            <div class="col-md-8 mt-4">
                                <label for="">Joining Date</label>
                                <div class="col-md-6" style="display: flex; position: absolute;padding-left: 0px;">
                                  <span class="mt-2">From</span> <input type="date" class="form-control" name="join_from_date"style="width: 74%;margin-left: 6px;">
                                </div>
                                <div class="col-md-6" style="display: flex; position: absolute; margin-left: 18pc;">
                                  <span class="mt-2">To</span> <input type="date" class="form-control" name="join_to_date" style="width: 74%;margin-left: 6px;">
                                </div>
                            </div>
                            <div class="col-md-3  mt-4">
                                <button type="submit" class="btn btn-primary mt-4">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width:18px !important; padding-left: 4px !important; padding-right: 27px !important;">SNo.</th>
                                            <th>Branch</th>
                                            <th>Emp Code</th>
                                            <th>Name</th>
                                            <th>DoJ</th>
                                            <th>Letter Type</th>
                                            <th>Letter Link</th>
                                            <th>Letter Ref. no.</th>
                                            <th>Letter Date&time</th>
                                            <th>SMS Status</th>
                                            <!--<th>Acceptance Status</th>-->
                                            <th>Acceptance Date & time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                                            <tr>
                                                <td><?=$sr++;?></td>
                                                <td><?=$row->branch_name;?></td>
                                                <td><?=$row->code;?></td>
                                                <td><?=$row->name;?></td>
                                                <td><?php echo date_format(date_create($row->doj),"d-m-Y");?></td>
                                                <td><?=$row->letter_name;?></td>
                                                <td><?php echo @$row->confirmation_status==1 ?'<a class="text-white btn-sm btn btn-success" target="_blank" href="'.base_url('apointment-letter/'.$this->Allmodel->encrypt($row->employee_id,KEY).'').'">Accepted</a>':'<a class="text-white btn-sm btn btn-danger" target="_blank" href="'.base_url('apointment-letter/'.$this->Allmodel->encrypt($row->employee_id,KEY).'').'">Pending</a>';?></td>
                                                <td><?=$row->letter_serial_number;?></td>
                                                <td><?php $created_date=date_create($row->letter_issue_date); echo date_format($created_date,"d-M-Y H:i:s");?></td>
    											<td><?php $var = json_decode($row->sms_report); echo $var->message;?></td>
                                                <!--<td></td>-->
                                                <td>
                                                    <?php
                                                    if(!empty($row->approved_date)){
                                                        echo date_format(date_create($row->approved_date),"d-M-Y H:i:s");
                                                    }
                                                     
                                                     ?></td>
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
    </div>
    
       <script>
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