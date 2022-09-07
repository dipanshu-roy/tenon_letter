<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href=#>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Roles</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">  
            <h6 class="card-title">Create New Role</h6>
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST">
                            <div class="form-group row">
                                <div class="col-sm-6  mb-3">
                                    <label class="">Role Name</label>
                                    <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                    <input type="text" name="name" class="form-control" value="<?php echo !empty($updates->name)? $updates->name:''?>" placeholder="Add Role Name">
                                </div> 
                                <div class="col-md-6 mb-3">
                                    <label for="">Status</label>
                                    <select class="form-control"  name="status" required="">
                                        <option value="">Select</option>
                                        <option value="1" <?php echo @$updates->status==1?'selected':''?>>Active</option>
                                        <option value="2" <?php echo @$updates->status==2?'selected':''?>>Inactive</option>
                                    </select>
                                </div>
                                <div class="col-sm-3">
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
                <h6 class="card-title">Role List</h6>
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                       <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                        <th>Role Name</th>
                        <th>Status</th>
                        <th>Created date & By</th>
                        <th>Updated date & By</th>
                        <th>Permission</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                    <tr>
                        <td><?=$sr++;?></td>
                        <td><?=$row->name;?></td>
                        <td><?php echo $row->status==1?'Active':'Inactive';?></td>
                        <td>
                            <?php $created_date=date_create($row->created_date); echo date_format($created_date,"d-m-Y H:i:s");?> </br> 
                            <?php
                                if($row->created_by > 2){
                                    $created_by=$this->Allmodel->selectrow('lt_system_users',array('staff_id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }else{
                                    $created_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                }
                            ?>
                        </td>
                        <td>
                            <?php if($row->updated_at ==''){ echo '';}else{ $updated_at=date_create($row->updated_at); echo date_format($updated_at,"d-m-Y H:i:s");}?>
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
                        <td><a href="<?=base_url('create-roles/map-user-role/'.$row->id.'');?>" class="btn btn-primary btn-sm text-white">Map Permission</a></td>
                        <td>
                            <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>Action 
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?=base_url('create-roles/'.$row->id.'');?>">Edit</a>
                              
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
    

</script>