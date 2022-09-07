<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>  
<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
    <div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href=#>Home</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Pending Letter Reports</li>
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
                                <select class="select2-example form-control" name="branch_id" id="branch_id" >
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
                            <div class="col-md-3">
                                <label for="">Date of Joining</label>
                                  <input type="date" class="form-control" name="doj">
                            </div>
                            <div class="col-md-3">
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
        <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                            <th>ID</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Branch Name</th>
                            <th>DOJ</th>
                            <th>Approval Date</th>                        
                            <th>Letter Type</th>                        
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $sr=1; 
                            if(!empty($guard)){ 
                            foreach($guard as $row){ 
                            if($row->employee_id==""){
                        ?>
                            <tr>
                                <td><?=$sr++;?></td>
                                <td><?=$row->id;?></td>
                                <td><?=$row->code;?></td>
                                <td><?=$row->name;?></td>
                                <td><?=$row->branch_name;?></td>
                                <td><?php echo date_format(date_create($row->doj),"d-m-Y");?></td>
                                <td><?=$row->approved_date;?></td>
                                <td><?=$row->letter_name;?></td>
                            </tr>
                        <?php } } } ?>
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <!-- <div class="card">
            <div class="card-body">
                <table id="example1" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                            <th>ID</th>
                            <th>On Board Emp code</th>
                            <th>Name</th>
                            <th>Phone Number</th>
                            <th>On Boarded Through App</th>
                            <th>Company</th>
                            <th>Approval Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sr=1; if(!empty($guard_master)){ foreach($guard_master as $row){ if(empty($row->employee_id)){ ?>
                            <tr>
                                <td><?=$sr++;?></td>
                                <td><?=$row->id;?></td>
                                <td><?=$row->On_Board_Emp_code;?></td>
                                <td><?=$row->name;?></td>
                                <td><?=$row->phone_number;?></td>
                                <?php if($row->On_Boarded_Through_App==1){
                                    echo '<td>'.$row->On_Boarded_Through_App.'</td>';
                                }else{
                                    echo '<td class="bg-danger" data-toggle="modal" data-target="#onboard" onclick="onboard_click('.$row->id.')">Fill On Board </td>';
                                }?>
                                <td><?=$row->company;?></td>
                                <?php if($row->approval_status=='Verified'){
                                    echo '<td>'.$row->approval_status.'</td>';
                                }else{
                                    echo '<td class="bg-danger" data-toggle="modal" data-target="#onboard" onclick="onboard_click('.$row->id.')">Please Verify </td>';
                                }?>
                            </tr>
                        <?php } } }  ?>
                    </tbody>
                </table>
            </div>
        </div> -->
<div id="onboard" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change On Boarded Through App</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="<?=base_url('admin/onboard');?>" method="POST">
                    <div class="form-group">
                        <span id="get_onboard"></span>
                        <label>On Boarded Through App:</label>
                        <input type="text" value="1" name="On_Boarded_Through_App" class="form-control">
                    </div>
                    <!-- <div class="form-group">
                        <label>Company</label>
                        <select name="company" class="form-control">
                            <option value="Tenon">Tenon</option>
                            <option value="Peregrine">Peregrine</option>
                        <select>
                    </div> -->
                    <div class="form-group">
                        <label>Approval Status</label>
                        <input type="text" value="Verified" name="approval_status" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
function onboard_click(id){
    $('#get_onboard').html('<input type="hidden" name="id" value="'+id+'" class="form-control">');
}
</script>
            