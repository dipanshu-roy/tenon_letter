   <div class="page-header">

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">

                    <a href="#">Home</a>

                </li>

                <li class="breadcrumb-item active" aria-current="page">

                    <a href="#">Letter Templates</a>

                </li>

            </ol>

        </nav>

        <a href="<?=base_url('create-templates');?>" class="btn btn-primary text-white" style="margin-left: 53%;">Create Letter Template</a>

    </div>

    <?php

        	$this->load->model('Allmodel');

    ?>

     <div class="row">

        <div class="col-md-12">

            <div class="row">

                <div class="col-md-12">

                    <div class="card">

                        <div class="card-body">
                              <h6 class="card-title">Letter Templates List</h6>
                            <table id="example" class="table table-striped table-bordered">

                                <thead>

                                    <tr>

                                        <th style="width: 18px !important;padding-left: 4px !important;padding-right: 27px !important;">SNo.</th>

                                        <th>Template Name</th>

                                        <th>Letter Name</th>

                                        <!--<th>Branch</th>-->

                                        <th>Language</th>

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

                                            <td><?=$row->template_name;?></td>

                                            <td><?php $letter_name=$this->Allmodel->selectrow('lt_letter_master',array('id'=>$row->letter_master_id)); echo $letter_name->letter_name; ?></td>


                                            <td><?php $language=$this->Allmodel->selectrow('lt_languages',array('id'=>$row->language)); echo $language->name; ?> (<?=$language->lang_code?>)</td>

                                            <td>
                                                <input type="hidden" name="xid" value="<?=$row->status;?>" class="xid_<?=$row->id?>">
                                                <button type="button" onclick="updateStatus(<?=$row->id?>)" id="<?=$row->id?>" class="text-white btn-sm status_checks btn <?php echo ($row->status == 0) ? "btn-danger" : "btn-success"; ?> " value="<?=$row->id;?>">
                                                    
                                                    <?php echo ($row->status == 0) ? "Inactive" : "Active"; ?>
                                                </button>
                                            </td> 

                                            <td>
                                                <?php $created_date=date_create($row->created_date); echo date_format($created_date,"d-m-Y H:i:s");?>
                                                </br>
                                                <?php
                                                    if($row->created_by > 2){
                                                        $created_by=$this->Allmodel->selectrow('system_users',array('id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                                    }else{
                                                        $created_by=$this->Allmodel->selectrow(' tbl_admin',array('id'=>$row->created_by)); echo 'by '.$created_by->name; 
                                                    }
                                                ?>
                                            </td>

                                            <td>
                                                <?php if($row->updated_at==''){ echo '';}else{ $updated_at=date_create($row->updated_at); echo date_format($updated_at,"d-m-Y H:i:s");}?>
                                               </br>
                                                <?php
                                                    if($row->updated_by !=''){
                                                        if($row->updated_by > 2){
                                                            $updated_by=$this->Allmodel->selectrow('system_users',array('id'=>$row->updated_by)); echo 'by '.$updated_by->name; 
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

                                                    <a class="dropdown-item" href="<?=base_url('create-templates/letter-template-edit/'.$row->id.'');?>">Edit</a>

                                                  

                                                </div>

                                                </div>
                                                
                                                <a href="<?=base_url('apointment-letter-preview/'.$row->id.'');?>" style="margin-top: 10px;" target="_blank" class="btn btn-success text-white" title="Preview">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                            </td>

                                        </tr>

                                    <?php } } ?>

                                </tfoot>

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
    function updateStatus($item){
      var status =$(".xid_"+$item).val();
        var msg = (status==1)? 'Inactive' : 'active'; 
        if(confirm("Are you sure to "+ msg)){
            var current_element = document.getElementById($item);
            if(status ==0){
                var st =1;
            }else{
                var st =0;
            }
            $.ajax({
                type:"POST",
                url: "<?=base_url('ajax/letter_template_list_status');?>", 
                data: {"id":$item,"status":st}, 
                success: function(data) { 
                    if(status ==0){
                        $(".xid_"+$item).val(1)
                        current_element.classList.remove('btn-danger');
                        current_element.classList.add('btn-success');
                        $("#"+$item).html('Active');
                    }else{  
                        $(".xid_"+$item).val(0)
                        current_element.classList.remove('btn-success');
                        current_element.classList.add('btn-danger');
                        $("#"+$item).text('Inactive');
                    }
                }  
            });
        }
        
    }

</script>

    

    

    <script src="https://cdn.ckeditor.com/4.14.0/standard/ckeditor.js"></script>



                            <script>

                                CKEDITOR.replace( 'description' );

                            </script>

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



