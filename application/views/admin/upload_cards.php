<link rel="stylesheet" href="<?=base_url('assets/');?>vendors/select2/css/select2.min.css" type="text/css">
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href=#>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Upload Cards</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Step-1</h5>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Card</label>
                                <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                    <select class="select2-example form-control" name="document_type" id="doc_excel" onchange="getExcle()" required>
                                        <option value="">Select</option>
                                        <option value="ESI Card" <?php if(!empty($updates->document_type)){ if($updates->document_type=='ESI Card'){  echo 'selected'; } } ?>>ESI Card</option>
                                        <option value="GHI Card" <?php if(!empty($updates->document_type)){ if($updates->document_type=='GHI Card'){  echo 'selected'; } } ?>>GHI Card</option>
                                    </select>
                                <div class="invalid-feedback d-block"> <?php echo form_error('document_type'); ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Choose CSV File</label>
                                <input type="file" name="doc_file" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12 text-right"> <span id="excel"></span></div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5>Step-2</h5>
                <form action="<?=base_url('admin/extract');?>" method="POST" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Card</label>
                                <input type="hidden" name="id"  value="<?php echo !empty($updates->id)? $updates->id:''?>">
                                    <select class="select2-example form-control" name="document_type" id="doc_zip" required>
                                        <option value="">Select</option>
                                        <option value="ESI_Card">ESI Card</option>
                                        <option value="GHI_Card">GHI Card</option>
                                    </select>
                                <div class="invalid-feedback d-block"> <?php echo form_error('document_type'); ?></div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Choose Zip File</label>
                                <input type="file" name="file" class="form-control">
                            </div>
                        </div>
                         <div class="col-md-12 text-right"> <span id="zip"><a href="<?=base_url('assets/document.zip');?>"><i class="fa fa-download"></i> Download Sample Zip File</a></span></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function getExcle(){
        var doc_val = $("#doc_excel").val();
  
        if(doc_val == 'ESI Card'){
            $("#excel").html('');
            $("#excel").append('<a href="<?=base_url('assets/esic-format.csv');?>"><i class="fa fa-download"></i> Download Sample CSV File</a>');
        }
        if(doc_val == 'GHI Card'){
            $("#excel").html('');
          $("#excel").append('<a href="<?=base_url('assets/ghi-format.csv');?>"><i class="fa fa-download"></i> Download Sample CSV File</a>');
        }
    }

</script>
