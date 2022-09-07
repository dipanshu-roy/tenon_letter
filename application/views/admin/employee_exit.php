<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href=#>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Employee To Exit</li>
        </ol>
    </nav>
</div>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
.show-amazing{
    background: #ffffff;
    border-color: #e3dede;
    font-size: 10px;
    color: #424040!important;
    padding: 1px 4px;
    text-align: left;
}
.multi-field {
    margin-bottom: 25px;
}
.multi-field:before{
    display: table;
    content: " ";
}
.multi-field:after{
    display: table;
    content: " ";
}
.btnmore{
    background-color: #008d4c;
    padding: 2px 10px;
    color: white;
}
#employee_already{
    font-size: 13px;
}
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">  
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="">Enter Employee Code</label>
                                <input type="text" name="employee_code" id="employee_code" class="form-control" placeholder="Enter Employee Code" value="<?php echo !empty($guard_update->code) ? $guard_update->code :'';?>" autocomplete="off">
                                <div class="invalid-feedback d-block"> <?php echo form_error('letter_title'); ?></div>
                                <div class="invalid-feedback d-block"><span id="employee_already"></span></div>
                            </div>
                            <div class="col-sm-3">
                                <br/><button onclick="get_employee_data()" type="submit" class="btn btn-primary mb-2">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row" id="employee_data">
                    <?php if(!empty($guard_update)){ 
                    $branch=$this->Allmodel->join_row("SELECT name FROM `default_cio_locations` WHERE id=$guard_update->branch LIMIT 1;");
                    echo '<div class="col-md-6 mb-2"><b>Name</b> : '.$guard_update->name.'</div><div class="col-md-6 mb-2"><b>Branch</b> : '.$branch->name.'</div><div class="col-md-6"><b>Address</b> : '.$guard_update->address.'</div><div class="col-md-6"><b>Date of joining</b> : '.$guard_update->doj.'</div>'; }?>
                </div>
                <br/>
                <form action="" method="POST" enctype="multipart/form-data">
                <div class="row show_approval" <?php echo !empty($guard_update->code) ? '':'style="display:none"';?>>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Date of resignation</b></label>
                            <span id="get_letters"><input type="hidden" name="letter_id" value="<?php echo !empty($update->letter_id) ? $update->letter_id :'';?>"><input type="hidden" name="letter_template_id" value="<?php echo !empty($update->letter_template_id) ? $update->letter_template_id :'';?>"></span>
                            <input type="hidden" name="gaurd_id" id="gaurd_id" <?php echo !empty($update->gaurd_id) ? "value=".$update->gaurd_id."" :'';?>>
                            <input type="hidden" name="id" value="<?php echo !empty($update->id) ? $update->id :'';?>">
                            <input type="text" name="date_of_resignation" id="date_of_resignation" class="form-control" placeholder="Date of resignation" value="<?php echo !empty($update->date_of_resignation) ? date_format(date_create($update->date_of_resignation),"d-M-Y") :'';?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><b>Date of last working</b></label>
                            <input type="text" name="date_of_last_working" id="date_of_last_working" class="form-control" placeholder="Date of last working" value="<?php echo !empty($update->date_of_last_working) ? date_format(date_create($update->date_of_last_working),"d-M-Y") :'';?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-12">
                        </br/>
                        <label><b>Uploaded Document</b></label>
                        <div class="multi-field-wrapper">
                            <div class="multi-fields">
                                    <?php if(!empty($update->document)){ 
                                        $document=json_decode($update->document);
                                        if(!empty($document)){
                                        foreach($document as $dc){ ?>
                                        <div class="multi-field">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <input type="text" name="filename[]" class="form-control" value="<?=$dc->file_name;?>" placeholder="Document Title" autocomplete="off">
                                                </div>
                                                <div class="col-md-6" style="width:40%;flex: 0 0 40%;">
                                                    <input type="file" name="filedata[]" class="form-control">
                                                </div>
                                                <?php if(!empty($dc->file_name)){ ?>
                                                    <a target="_blank" class="float-right" href="<?=$dc->filedata;?>"><i class="fa fa-eye"></i></a>
                                                <?php } ?>
                                            </div>
                                            <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:4%;margin-top: -33px;"><i class="fa fa-trash"></i></button>
                                        </div>
                                        <?php } }
                                    }else{ ?>
                                    <div class="multi-field">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="text" name="filename[]" class="form-control" placeholder="Document Title" autocomplete="off">
                                            </div>
                                            <div class="col-md-6" style="width:40%;flex: 0 0 40%;">
                                                <input type="file" name="filedata[]" class="form-control">
                                            </div>
                                        </div>
                                        <button type="button" class="remove-field btn-danger btn-sm float-right" style="width:4%;margin-top: -33px;"><i class="fa fa-trash"></i></button>
                                    </div>
                                    <?php } ?>
                            </div>
                            <?php if(!empty($update->document)){?>
                            <p class="text-danger">Note: If you want to update then you have to select document again</p>
                            <?php } ?>
                            <button type="button" class="add-field remove-field btn-success btn-sm" style="padding: 0rem 2.5rem"><i class="fa fa-plus"></i> Add More</button>
                            <br/>
                        </div>
                        
                    </div>
                    <div class="col-sm-3">
                        <br/><button type="submit" class="btn btn-primary mb-2">Submit</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Exit Employee History</h6>
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                       <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Emp Name</th>
                        <th>Date of resignation</th>
                        <th>Date of last working</th>
                        <th>Document <small style="display: inline-flex;">( Click to Open )</small></th>
                        <!-- <th>Status</th> -->
                        <th>Created date & By</th>
                        <th>Experience letter status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                    <tr>
                        <td><?=$sr++;?></td>
                        <td><?=$row->name;?> <br/>( <?=$row->code;?> )</td>
                        <td><?=date_format(date_create($row->date_of_resignation),"d-M-Y");?></td>
                        <td><?=date_format(date_create($row->date_of_last_working),"d-M-Y");?></td>
                        <td>
                            <?php if(!empty($row->document)){ 
                                $document=json_decode($row->document);
                                if(!empty($document)){
                                foreach($document as $dc){ ?>
                                <a href="<?=$dc->filedata;?>" download><span  class="btn show-amazing"><?=$dc->file_name;?></span></a></br>
                            <?php } } }?>
                        </td>
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
                            <?php if($row->exit_satus==0){ ?>
                                <a class="btn btn-warning btn-sm text-white" onclick="return confirm('Are you sure you want to generate experience letter. ?');" href="<?=base_url('admin/generateletter/'.$row->id.'/'.$row->gaurd_id.'');?>">Generate</a>
                            <?php }else{ ?>
                                <a class="text-success">Generated</a>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>Action 
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?=base_url('employee-exit/'.$row->id.'');?>">Edit</a>
                                <?php if($row->exit_satus==1){ ?>
                                <a class="dropdown-item" target="_blank" href="<?=base_url('apointment-letter/');?><?=$this->Allmodel->encrypt($row->gaurd_id,KEY);?>/<?=$row->letter_id;?>/<?=$row->letter_template_id;?>">View Letter</a>
                                <?php } ?>
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
function get_employee_data(){
    $('.show_approval').hide();
    $.ajax({
        url:'<?=base_url('Ajax/get_employee_data');?>',
        type:'POST',
        dataType:'json',
        data:{employee_code:$('#employee_code').val()},
        success: function(xhr) { 
            if(xhr.status==200){
                if(xhr.exit>0){
                    $('#employee_already').text('Employee already resigned');
                }else{
                    $('#employee_already').text('');
                    $('.show_approval').show(); 
                    $('#employee_data').html('<div class="col-md-6 mb-2"><b>Name</b> : '+xhr.name+'</div><div class="col-md-6 mb-2"><b>Branch</b> : '+xhr.branch+'</div><div class="col-md-6"><b>Address</b> : '+xhr.address+'</div><div class="col-md-6"><b>Date of joining</b> : '+xhr.doj+'</div>');
                    $('#gaurd_id').val(xhr.id);
                    $('#get_letters').html('<input type="hidden" name="letter_id" value="'+xhr.letters.letter_mater_id+'"><input type="hidden" name="letter_template_id" value="'+xhr.letters.letter_template_id+'">');
                }
            }else{
                $('#employee_data').html('No data Found');
                $('.show_approval').hide();
            }
        }
    });
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
<script>
$('.multi-field-wrapper').each(function() {
    var $wrapper = $('.multi-fields', this);
    $(".add-field", $(this)).click(function(e) {
        $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
    });
    $('.multi-field .remove-field', $wrapper).click(function() {
        if ($('.multi-field', $wrapper).length > 1)
            $(this).parent('.multi-field').remove();
    });
});
</script>
<script>
  $( function() {
    $( "#date_of_resignation").datepicker({
        dateFormat: 'dd-MM-yy',
        autoclose: true,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() + 1);
            $("#date_of_last_working").datepicker("option", "minDate", dt);
        }
    });
    $( "#date_of_last_working").datepicker({
        dateFormat: 'dd-MM-yy',
        autoclose: true,
        onSelect: function (selected) {
            var dt = new Date(selected);
            dt.setDate(dt.getDate() - 1);
            $("#date_of_resignation").datepicker("option", "maxDate", dt);
        }
    });
  } );
</script>