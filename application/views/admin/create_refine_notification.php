<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
 <script src="https://code.jquery.com/jquery-1.10.2.js"></script> 
<style>
.show-amazing{
    background: #ffffff;
    border-color: #e3dede;
    font-size: 10px;
    color: #424040!important;
    padding: 1px 4px;
    text-align: left;
}
.custom-switch .custom-control-label::before {
    background-color: #aa66cc!important;
    color: white;
    border: #aa66cc solid 1px!important;
}
.custom-switch .custom-control-label::after{
    background-color: #ffffff;
}
</style>
<div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#">Refyne Notification Template</a>
                </li>
            </ol>
        </nav>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <?php if($this->session->userdata('s_admin')->id!=3){ ?>
                    <div class="card">
                        <div class="card-body">
                            <form class="needs-validation" method="POST" novalidate="" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-group d-flex">
                                            <label style="margin-top: 4px;">Send To Branch &nbsp;</label>
                                            <div class="custom-control custom-switch custom-checkbox-secondary">
                                                <input type="checkbox" class="custom-control-input" name="tenon_connect" value="1" id="tenon_connect">
                                                <label class="custom-control-label" for="tenon_connect"> Send To Company </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Unique ID</label>
                                        <input type="text" maxlength="100" class="form-control" name="unique_id" id="unique_id" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)" placeholder="Enter Unique ID" required="">
                                        <span id="unique_id_error" style="color:red"></span>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label>Template ID</label>
                                        <input type="text" maxlength="100" class="form-control" name="template_id" id="template_id" onkeypress="return /[0-9a-zA-Z]/i.test(event.key)" placeholder="Enter Template ID" required="">
                                    </div>
                                    <div class="col-md-6 mb-3" id="branch_wise">
                                        <label>Select Branch</label>
                                        <select name="group_id[]" class="form-control select2-example group_id" width="100%" multiple="" id="group_id" onchange="groupid(this)">
                                            <?php if(!empty($branch)){ foreach($branch as $row){ ?>
                                            <option value="<?=$row->id;?>"><?=$row->name;?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3" id="company_wise" style="display:none">
                                        <label>Select Company</label>
                                        <select name="company_id" class="form-control" width="100%" id="company_id">
                                        <option value="">--Select Company--</option>
                                        <option value="1">Tenon</option>
                                        <option value="2">Peregrine</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Image</label>
                                        <span id="show_images"><input type="file" class="form-control" name="image"></span>
                                        <span id="images"></span>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label>Message Title ( <span id="charsLeftTitle">60</span> )</label>
                                        <input type="text" maxlength="60" onkeyup="countCharTitle(this)" id="msg_title" class="form-control" name="msg_title" placeholder="Message Title" required="">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label>Description ( <span id="charsLeft">225</span> )</label>
                                        <textarea class="form-control" onkeyup="countChar(this)" maxlength="225" name="description" id="description" required=""></textarea>
                                    </div>
                                    
                                </div>
                                <button class="btn btn-primary btnsubmit" type="button">Submit</button>
                            </form>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                             <h6 class="card-title w-50">Notification List</h6>
                             
                            </div>
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                                        <th>Unique ID</th>
                                        <th>Template ID</th>
                                        <th>Message Title</th>
                                        <th>Message Description</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Created Date & By</th>
                                        <?php if($this->session->userdata('s_admin')->id!=3){ ?><th>Action</th><?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                                        <tr>
                                            <td><?=$sr++;?></td>
                                            <td><?=$row->unique_id;?></td>
                                            <td><?=$row->template_id;?></td>
                                            <td><?=$row->msg_title;?></td>
                                            <td><?=$row->msg_desc;?></td>
                                            <td><img src="<?=base_url('assets/media/image/'.$row->image);?>" style="width:40px;height:40px"></td>
                                            <td>
                                                <input type="hidden" name="xid" value="<?=$row->status;?>" class="xid_<?=$row->id?>">
                                                <button type="button" onclick="updateStatus(<?=$row->id?>)" id="<?=$row->id?>" class="text-white btn-sm status_checks btn <?php echo ($row->status == 1) ? "btn-danger" : "btn-success"; ?> " value="<?=$row->id;?>">
                                                    <?php echo ($row->status == 1) ? "Inactive" : "Active"; ?>
                                                </button>
                                            </td> 
                                            <td><?php $created_date=date_create($row->created_at); echo date_format($created_date,"d-m-Y H:i:s");?> </br> 
                                                <?php
                                                if($row->user_id > 2){
                                                    $users=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->user_id)); echo 'by '.@$users->name; 
                                                }else{
                                                    $users=$this->Allmodel->selectrow('tbl_admin',array('id'=>$row->user_id)); echo 'by '.@$users->name; 
                                                }
                                                ?>
                                            </td>
                                            <?php if($this->session->userdata('s_admin')->id!=3){ ?>
                                            <td>
                                                <div class="btn-group">
                                                <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="sr-only">Toggle Dropdown</span>Action 
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" data-toggle="modal" data-target="#myModal<?=$row->id;?>" href="#">View</a>
                                                    <a class="dropdown-item" onclick="return confirm('Are you sure to delete')" href="<?=base_url('admin/delete_refyne_notification/'.$row->id.'');?>">Delete</a>
                                                </div>
                                                </div>
                                            </td>
                                            <?php } ?>
                                        </tr>
                                        <div id="myModal<?=$row->id;?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Branch Name</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <?php
                                                    if(!empty($row->group_id)){
                                                        $trmin=rtrim($row->group_id,',');
                                                            $branch=$this->db->query("SELECT name FROM `default_cio_locations` WHERE id in ($trmin)")->result();
                                                            if(!empty($branch)){ foreach($branch as $bra){
                                                            echo '<span  class="btn show-amazing" style="display: flex;">'.$bra->name.'</span>';
                                                        } }
                                                    }?>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } } ?>
                                </tbody>
                            </table>
                            <?php if(!empty($total_pages)) { ?>
                            <ul class="pagination">
                                <li class="paginate_button page-item previous">
                                    <a class="page-link" href="?pageno=1">First</a>
                                </li>
                                <li class="paginate_button page-item previous <?php if($pageno <= 1){ echo 'disabled'; }else{ echo 'active';} ?>">
                                    <a class="page-link" href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pageno=".($pageno - 1); } ?>">Prev</a>
                                </li>
                                <li class="paginate_button page-item next <?php if($pageno >= $total_pages){ echo 'disabled'; }else{ echo 'active';} ?>">
                                    <a class="page-link" href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pageno=".($pageno + 1); } ?>">Next</a>
                                </li>
                                <li class="paginate_button page-item next"><a class="page-link" href="?pageno=<?php echo $total_pages; ?>">Last</a></li>
                            </ul>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
$(document).ready(function(){
    $("#tenon_connect").change(function(){
        if($(this).is(':checked')){
            $('#branch_wise').hide();
            $('#company_wise').show();
        }else{
            $('#branch_wise').show();
            $('#company_wise').hide();
            function groupid(id) {
                var group_id = $('#group_id').val().length;
                if(group_id>0){
                    $('.btnsubmit').attr('type', 'submit');
                }else{
                    $('.btnsubmit').attr('type', 'button');
                }
            }
        }
    });
});
$(document).on('click','.btnsubmit',function(){
    var group_id = $('#group_id').val().length;
    var company_id = $('#company_id').val().length;
    if(group_id>0){
        $('.btnsubmit').attr('type', 'submit');
    }else if(company_id>0){
        $('.btnsubmit').attr('type', 'submit');
    }else{
        setTimeout(function () {
            toastr.options = {
                timeOut: 2000,
                progressBar: true,
                showMethod: "slideDown",
                hideMethod: "slideUp",
                showDuration: 200,
                hideDuration: 200,
                positionClass: "toast-top-center"
            };
            toastr.error('Please select atleast one branch || Company');
        }, 500);
        $('.btnsubmit').attr('type', 'button');
    }
});
$(document).on('keyup','#template_id',function(){
    if ($("#template_id").val().length > 1) {
      $.ajax({
        type: "post",
        url: "<?=base_url('ajax/check_template');?>",
        cache: false,
        data: 'template_id=' + $("#template_id").val(),
        dataType: "json",
        success: function(xhr) {
          if(xhr.status==200){
            $('#show_images').html('<a href="<?=base_url('assets/media/image/');?>'+xhr.data.image+'" target="_blank"><img src="<?=base_url('assets/media/image/');?>'+xhr.data.image+'" style="width:40px"></a>');
            $('#images').html('<input type="hidden" class="form-control" name="images" value="'+xhr.data.image+'">');
            $('#msg_title').val(xhr.data.msg_title);
            $('#description').val(xhr.data.msg_desc);
            
            $('#msg_title').attr('readonly', true);
            $('#description').attr('readonly', true);
          }else{
            $('#show_images').html('<input type="file" class="form-control" name="image">');
            $('#images').html('');
            $('#msg_title').val('');
            $('#description').val('');
            
            $('#msg_title').attr('readonly', false);
            $('#description').attr('readonly', false);
          }
        }
      });
    }
});
$(document).on('keyup','#unique_id',function(){
    if ($("#unique_id").val().length > 1) {
      $.ajax({
        type: "post",
        url: "<?=base_url('ajax/check_unique_id');?>",
        cache: false,
        data: 'unique_id=' + $("#unique_id").val(),
        dataType: "json",
        success: function(xhr) {
          if(xhr.status==200){
            $('#unique_id_error').text('Unique ID already available');
            $('#group_id').attr('readonly', true);
            $('#template_id').attr('readonly', true);
            $('#msg_title').attr('readonly', true);
            $('#description').attr('readonly', true);
            $('.btnsubmit').attr('disabled', true);
          }else{
            $('#unique_id_error').text('');
            $('#group_id').attr('readonly', false);
            $('#template_id').attr('readonly', false);
            $('#msg_title').attr('readonly', false);
            $('#description').attr('readonly', false);
            $('.btnsubmit').attr('disabled', false);
          }
        }
      });
    }
});
<?php if($this->session->userdata('s_admin')->id!=3){ ?>
function updateStatus($item){
      var status =$(".xid_"+$item).val();
    var msg = (status==0)? 'Inactive' : 'active'; 
    if(confirm("Are you sure to "+ msg)){
        var current_element = document.getElementById($item);
        if(status ==0){
            var st =1;
        }else{
            var st =0;
        }
        $.ajax({
            type:"POST",
            url: "<?=base_url('ajax/refine_notification_status');?>", 
            data: {"id":$item,"status":st}, 
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
<?php } ?>
function countChar(val) {
  var len = val.value.length;
  if (len >= 225) {
    val.value = val.value.substring(0, 225);
  } else {
    $('#charsLeft').text(225 - len);
  }
};
function countCharTitle(val) {
  var len = val.value.length;
  if (len >= 60) {
    val.value = val.value.substring(0, 60);
  } else {
    $('#charsLeftTitle').text(60 - len);
  }
};
</script>
<script>
// $("#group_id").change(function() {
    // alert("test");
    // var group_id = $('#group_id').val().length;
    // if(group_id>0){
    //     $('.btnsubmit').attr('type', 'submit');
    // }else{
    //     setTimeout(function () {
    //         toastr.options = {
    //             timeOut: 2000,
    //             progressBar: true,
    //             showMethod: "slideDown",
    //             hideMethod: "slideUp",
    //             showDuration: 200,
    //             hideDuration: 200,
    //             positionClass: "toast-top-center"
    //         };
    //         toastr.error('Please select atleast one branch');
    //     }, 500);
    //     $('.btnsubmit').attr('type', 'button');
    // }
// });
$(document).ready(function() {
    $('#example').DataTable({
        "lengthMenu": [[50,100, 200, -1], [50,100, 200, "All"]],
        "dom": 'Bfrtip',
        scrollY: false,
        scrollX: true, 
        scroller: true,
        buttons: ['csv', 'pageLength']
    } );
} );
</script>

