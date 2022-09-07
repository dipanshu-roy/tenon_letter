<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href=#>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Issue Other Letter</li>
        </ol>
    </nav>
</div>
<style>
.show-amazing{
    background: #ffffff;
    border-color: #e3dede;
    font-size: 10px;
    color: #424040!important;
    padding: 1px 4px;
    text-align: left;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">  
            <h6 class="card-title">Issue Other Letter</h6>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-group row">
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
                                    <label for="">Select Employee</label>
                                    <select class="select2-example form-control"  name="employee_id[]" id="employee_id" multiple="" required="">
                                        <?php if(!empty($updates->employee_id)){ $employee_id=explode(',',$updates->employee_id);
                                            $employee=count($employee_id);
                                            if(!empty($guard)){ $sr=0; foreach($guard as $grd){?>
                                            <option value="<?=$grd->id;?>"
                                            <?php if($employee_id[$sr++]==$grd->id){ echo 'selected'; } ?>><?=$grd->name;?> ( <?=$grd->code;?> )</option>
                                        <?php } } } ?>
                                    </select>
                                    <div class="invalid-feedback d-block"> <?php echo form_error('employee_id'); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Purpose of letter</label>
                                    <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                    <input type="text" name="letter_title" value="<?php echo !empty($updates->letter_title)? $updates->letter_title:''?>" class="form-control">
                                    <div class="invalid-feedback d-block"> <?php echo form_error('letter_title'); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="">Upload File</label>
                                    <input type="file" name="letter" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <br/>
                                    <button type="submit" class="btn btn-primary mb-2">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Issued Other Letter List</h6>
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                       <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Purpose of letter</th>
                        <th>Emp Name</th>
                        <th>View Letter</th>
                        <!-- <th>Status</th> -->
                        <th>Created date & By</th>
                        <th>Updated date & By</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                    <tr>
                        <td><?=$sr++;?></td>
                        <td><?=$row->letter_title;?></td>
                        <td><?php if(!empty($row->employee_id)){
                            $datas2=$this->db->query("SELECT name,code FROM `guard_master` WHERE id in ($row->employee_id) GROUP BY `name` ASC")->result();
                            if(!empty($datas2)){
                                foreach($datas2 as $grd){
                                    echo '<span  class="btn show-amazing">'.$grd->name.' <br/>( '.$grd->code.' )</span></br>';
                                }
                            }
                        }
                        ?>
                        </td>
                        <td><a class="btn btn-success btn-sm text-white" target="_blank" href="<?=base_url('assets/OtherLetters/'.$row->letter.'');?>" download>Download</a></td>
                        <!-- <td><?php echo $row->status==1?'Active':'Inactive';?></td> -->
                        <td>
                            <?php $created_date=date_create($row->created_date); echo date_format($created_date,"d-m-Y H:i:s");?> </br> 
                            <?php
                                if($row->created_by > 2){
                                    $created_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }else{
                                    $created_by=$this->Allmodel->selectrow('tbl_admin',array('id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }
                            ?>
                        </td>
                        <td>
                            <?php if($row->update_date ==''){ echo '';}else{ $updated_at=date_create($row->update_date); echo date_format($updated_at,"d-m-Y H:i:s");}?>
                            </br> 
                            <?php
                            if($row->updated_by !=''){
                                if($row->updated_by > 2){
                                    $updated_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->updated_by)); echo 'by '.$updated_by->name; 
                                }else{
                                    $updated_by=$this->Allmodel->selectrow('tbl_admin',array('id'=>$row->updated_by)); echo 'by '.$updated_by->name; 
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
                                <a class="dropdown-item" href="<?=base_url('other-letters/'.$row->id.'');?>">Edit</a>
                              
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
$(document).ready(function() {
    $('#example').DataTable({
        "lengthMenu": [[50,100, 200, -1], [50,100, 200, "All"]],
        "dom": 'Bfrtip',
        buttons: ['csv', 'pageLength']
    } );
} );
// $(document).ready(function(){
//     $('#company_id').change(function(){
//         $('#employee_id').val('');
//         $.ajax({
//             url:'<?=base_url('Ajax/getEmployeeName');?>',
//             type:'POST',
//             dataType:'html',
//             data:{company_id:$(this).val()},
//             success: function(responce) { 
//                 $('#employee_id').html(responce);
//             }
//         });
//     });
// });
function getofficer(){
    var current_element = $('#branch_id').val();
    $("#officer_id").empty('');
    $.ajax({
        type:"POST",
        url: "<?=base_url('ajax/getEmployeeName');?>", 
        data: {"id":current_element}, 
        success: function(data) { 
            $("#employee_id").html(data);
        } 
    });
}
</script>