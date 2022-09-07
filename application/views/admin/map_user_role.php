<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href=#>Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Map Permission</li>
        </ol>
    </nav>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST">
                            <div class="row">
                                <input type="hidden" name="role_id" id="role_id" value="<?=$this->uri->segment(3);?>">
                                <?php if(!empty($roll_menu)){ foreach($roll_menu as $key=>$rows){?>
                                <div class="col-md-4 mt-3">
                                    <div class="form-group">
                                        <div class="custom-control custom-switch custom-checkbox-secondary">
                                            <input type="checkbox" class="custom-control-input" name="key[]" value="<?=$key;?>" id="<?=$key;?>"
                                            <?php if(!empty($updates)){ foreach($updates as $row){ if($row->key==$key){
                                                echo 'checked';
                                            } } }?>
                                            >
                                            <label class="custom-control-label" for="<?=$key;?>"><?=$rows;?> </label>
                                        </div>
                                    </div>
                                </div>
                                <?php } } ?>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>