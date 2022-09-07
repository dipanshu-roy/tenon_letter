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

</style>
<div class="page-header">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="#"> Refyne Delivery Reports</a>
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
                            <div class="d-flex">
                             <h6 class="card-title w-50">Delivery Reports</h6>
                             <span class="w-50">
                            </span>
                            </div>
                            <table id="example" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>
                                        <th>Unique ID</th>
                                        <th>Template ID</th>
                                        <th>Success</th>
                                        <th>Failure</th>
                                        <th>Sent Date</th>
                                        <th>Sent Details</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr=1; if(!empty($result)){ foreach($result as $row){ ?>
                                        <tr>
                                            <td><?=$sr++;?></td>
                                            <td><?=$row->unique_id;?></td>
                                            <td><?=$row->template_id;?></td>
                                            <td><?php $datas=$this->db->query("SELECT COUNT(id) as id FROM lt_sent_notification_history WHERE msg_id='$row->unique_id' AND msg_status='success'")->row(); echo $datas->id;?></td>
                                            <td><?php $datas1=$this->db->query("SELECT COUNT(id) as id FROM lt_sent_notification_history WHERE msg_id='$row->unique_id' AND msg_status='failure'")->row(); echo $datas1->id;?></td>
                                            <td><?php $created_date=date_create($row->trigger_at); echo date_format($created_date,"d-m-Y H:i:s");?></td>
                                            <td style="white-space: nowrap;">
                                                <br/>
                                                <span style="border-bottom:1px solid #cdbebe;padding: 3px 0px"><b>Title</b>:<?=$row->msg_title;?><br/></span>
                                                <span style="border-bottom:1px solid #cdbebe;padding: 3px 0px"><b>Description</b>:<?=$row->msg_desc;?><br/></span>
                                                <b>Image</b>:
                                                <?php if(!empty($row->image)){?>
                                                    <a target="_blank" href="<?=$row->image;?>" style="color:blue"> Click here</a>
                                                <?php }else{
                                                    echo 'N/A';
                                                }?><br/>
                                            </td>
                                            <td>
                                                <a class="text-white btn-sm status_checks btn btn-primary" href="<?=base_url('admin/export_in_csv/'.$row->unique_id.'');?>">Download Report &nbsp;<i class="fa fa-cloud-download" style="font-size:22px" aria-hidden="true"></i><a/>
                                                <a class="btn btn-sm btn-success" data-toggle="modal" data-target="#myModal<?=$row->id;?>" href="#">View</a>
                                            </td>
                                        </tr>
                                        <div id="myModal<?=$row->id;?>" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Branch Name</h4>
                                                </div>
                                                <div class="modal-body">
                                                <?php if(!empty($row->group_id)){
                                                	$variable=explode(",", $row->group_id);
                                        			$branch=implode(",", $variable);
                                        			$results=$this->db->query("SELECT name FROM `default_cio_locations` WHERE `id` in ($branch)")->result();
                                        			if(!empty($results)){
                                            			foreach($results as $branch){
                                                            echo '<span  class="btn show-amazing">'.$branch->name.'</span><br/>';
                                            			}
                                            		}
                                                }?>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
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
</script>
