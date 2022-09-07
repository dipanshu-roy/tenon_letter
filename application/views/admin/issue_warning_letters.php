    <link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href=#>Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Issue Warning</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                  <h6 class="card-title">Issue Warning</h6>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Branch</label>
                                        <select class="select2-example form-control" name="branch_id" id="branch_id" onchange="getofficer()">
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
                                    <div class="form-group">
                                        <label>Employee Name</label>
                                        <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                        <select id="officer_id" class="select2-example select2_example_emp form-control staff_master" name="employee_id" onchange="getofficer_site()">
                                            <option value="">Select</option>
                                            <?php foreach($staff as $staff_data){ ?>                                                
                                                <option value="<?=@$staff_data->id?>"
                                                <?php if(@$updates->employee_id==@$staff_data->id){
                                                    echo 'selected';}?>><?=$staff_data->name?> ( <?=$staff_data->code?> )</option>
                                                <?php }?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('employee_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Site Name</label>
                                        <select id="site_id" class="select2-example form-control staff_master" name="site_id" >
                                            <option value="">Select</option>
                                            <?php foreach($cio_locations as $cio){ ?>                                                
                                                <option value="<?=@$cio->id?>"
                                                <?php if(@$updates->site_id==@$cio->id){
                                                    echo 'selected';}?>><?=$cio->name?></option>
                                                <?php }?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('site_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Reason</label>
                                                <select class="select2-example form-control" name="warning_reason" id="warning_reason">
                                                    <option value="">Select</option>
                                                    <?php if(!empty($resions)){ foreach($resions as $resions_row){?>
                                                    <option value="<?=$resions_row->id;?>" <?php if(!empty($updates->warning_reason)){
                                                        if($updates->warning_reason==$resions_row->id){
                                                            echo 'selected';
                                                        }
                                                    } ?>><?=$resions_row->reason;?></option>
                                                    <?php } } ?>
                                                </select>
                                                <div class="invalid-feedback d-block"> <?php echo form_error('warning_reason'); ?></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Incident date</label>
                                                <input type="date" class="form-control" name="incident_date" value="<?php echo !empty($updates->incident_date)? $updates->incident_date:''?>">
                                                <div class="invalid-feedback d-block"> <?php echo form_error('incident_date'); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Letter Type</label>
                                        <select class="select2-example form-control" name="letter_id" id="letter_mater_id" onchange="getTemplates()">
                                            <option value="">Select</option>
                                            <?php if(!empty($letter_master)){ foreach($letter_master as $letter_master_row){?>
                                            <option value="<?=$letter_master_row->id;?>" <?php if(!empty($updates->letter_id)){
                                                if($updates->letter_id==$letter_master_row->id){
                                                    echo 'selected';
                                                }
                                            } ?>><?=$letter_master_row->letter_name;?></option>
                                            <?php } } ?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('letter_id'); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Letter Template</label>
                                        <select class="select2-example form-control" name="letter_template_id" id="letter_template_id">
                                            <option value="">Select</option>
                                            <?php if(!empty($up_letter_template)){ foreach($up_letter_template as $ltr_tem){?>
                                            <option value="<?=$ltr_tem->id;?>"
                                                <?php if($updates->letter_template_id==$ltr_tem->id){
                                                    echo 'selected';} ?>><?=$ltr_tem->template_name;?> ( <?=$ltr_tem->lang_code;?> - <?=$ltr_tem->name;?> )</option>
                                            <?php } }?>
                                        </select>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('letter_template_id'); ?></div>
                                    </div>
                                </div>
                                 <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Remark</label>
                                        <textarea name="remark" class="form-control"><?php echo !empty($updates->remark)? $updates->remark:''?></textarea>
                                        <div class="invalid-feedback d-block"> <?php echo form_error('remark'); ?></div>
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
                  <h6 class="card-title">Issued Warning Letters List</h6>
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                       <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Employee</th>
                        <th>Incident Date On Site</th>
                        <th>View Date</th> 
                        <th>Letter</th>
                        <th>Template Name</th>
                        <th>Reason</th>
                        <th>Created date & By</th>
                        <th>Remark</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                    <tr>
                        <td><?=$sr++;?></td>
                        <td><?=$row->name;?></td>      
                        <td><?=$row->site_name;?><br>On <?php $incident_date=date_create($row->incident_date); echo date_format($incident_date,"d-M-Y");?></td>   
                        <td>
                            <?php 
                                $get_letters=$this->db->query("SELECT view_date FROM `lt_track_letters` WHERE is_view=1 AND employee_id=$row->employee_id AND letter_id=$row->letter_id AND letter_template_id=$row->letter_template_id")->row();
                                if(!empty($get_letters)){
                                    $view_date=date_create($get_letters->view_date); echo date_format($view_date,"d-M-Y");
                                }
                            ?>
                        </td> 
                        <td><?=$row->letter_name;?></td>
                          
                        <td><?=$row->template_name;?> <br/>( <?=@$row->lang_code;?> <?=@$row->lang_name;?> )</td>   
                        <td><?=$row->reason;?></td>  
                        <td>
                            <?php $created_date=date_create($row->created_date); echo date_format($created_date,"d-m-Y H:i:s");?> </br> 
                            <?php
                                if($row->created_by > 2){
                                    $created_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->created_by)); echo 'by '.@$created_by->name; 
                                }else{
                                    $created_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->created_by)); echo 'by '.@$created_by->name; 
                                }
                            ?>
                        </td>
                        <td><?=$row->remark;?></td> 
                        <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>Action 
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?=base_url('issue-warning-letters/'.$row->id.'');?>">Edit</a>
                                <a class="dropdown-item" href="<?=base_url('apointment-letter/');?><?=$this->Allmodel->encrypt($row->employee_id,KEY);?>/<?=$row->letter_id;?>">View Letter</a>
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
<script type="text/javascript" class="js-code-placeholder">
$(".select2_example_emp").select2({
  ajax: {
    url: "<?=base_url('ajax/get_employee');?>",
    dataType: 'json',
    delay: 250,
    data: function (params) {
      return {
        q: params.term, // search term
        emp:$("#branch_id").val(),
        page: params.page
      };
    },
    processResults: function (data, params) {
        // console.log(params);
      // parse the results into the format expected by Select2
      // since we are using custom formatting functions we do not need to
      // alter the remote JSON data, except to indicate that infinite
      // scrolling can be used

      return {
        results: data.items,
      };
    },
    cache: true
  },
  placeholder: 'Select',
  minimumInputLength: 1,
  templateResult: formatRepo,
  templateSelection: formatRepoSelection
});

function formatRepo (repo) {
    console.log(repo);
  if (repo.loading) {
    return repo.text;
  }
  var $container = $(
    "<div class='select2-result-repository clearfix'>" +
        "<div class='select2-result-repository__title'></div>" +
    "</div>"
  );
  $container.find(".select2-result-repository__title").text(repo.name);
  return $container;
}

function formatRepoSelection (repo) {
  return repo.full_name || repo.text;
}
</script>
<script>

function updateStatus($item){
      var status =$(".xid_"+$item).val();
    var msg = (status==0)? 'Inactive' : 'active'; 
    if(confirm("Are you sure to "+ msg)){
        var current_element = document.getElementById($item);
        $.ajax({
            type:"POST",
            url: "<?=base_url('ajax/map_officer_letter_status');?>", 
            data: {"id":$item,"status":status}, 
            success: function(data) { 
                if(status ==1){
                    $(".xid_"+$item).val(0)
                    current_element.classList.remove('btn-danger');
                    current_element.classList.add('btn-success');
                    $("#"+$item).html('Active');
                }else{  
                    $(".xid_"+$item).val(1)
                    current_element.classList.remove('btn-success');
                    current_element.classList.add('btn-danger');
                    $("#"+$item).text('Inactive');
                }
            }  
        });
    }
    
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
 function getofficer(){
    var current_element = $('#branch_id').val();
    $("#officer_id").empty('');
    $.ajax({
        type:"POST",
        url: "<?=base_url('ajax/getGuard');?>", 
        data: {"id":current_element}, 
        success: function(data) { 
            $("#officer_id").html(data);
        } 
    });
}
function getofficer_site() {
    var officer_id = $('#officer_id').val();
    $.ajax({
        type:"POST",
        url: "<?=base_url('ajax/getsite_id');?>", 
        data: {officer_id:officer_id}, 
        success: function(data) { 
            $("#site_id").html(data);
        } 
    });
}
</script>