</div>

            <!-- begin::footer -->
            <footer class="content-footer">
                <div>© <?=APP_NAME;?></div>
                <!--<div>-->
                <!--    <nav class="nav">-->
                <!--        <a href="#" class="nav-link">Licenses</a>-->
                <!--        <a href="#" class="nav-link">Change Log</a>-->
                <!--        <a href="#" class="nav-link">Get Help</a>-->
                <!--    </nav>-->
                <!--</div>-->
            </footer>
            <!-- end::footer -->

        </div>

    </div>

</div>
<!-- end::main -->

<!-- Plugin scripts -->
    <script src="<?=base_url('assets/');?>vendors/bundle.js"></script>


    <!-- Apex chart -->
    <script src="<?=base_url('assets/');?>js/irregular-data-series.js"></script>
    <script src="<?=base_url('assets/');?>vendors/charts/apex/apexcharts.min.js"></script>

    <!-- Daterangepicker -->
    <script src="<?=base_url('assets/');?>vendors/datepicker/daterangepicker.js"></script>


    <script src="<?=base_url('assets/');?>vendors/dataTable/datatables.min.js"></script>


    <script src="<?=base_url('assets/');?>js/examples/dashboard.js"></script>

 
    <script src="<?=base_url('assets/');?>vendors/vmap/jquery.vmap.min.js"></script>
    <script src="<?=base_url('assets/');?>vendors/vmap/maps/jquery.vmap.usa.js"></script>
    <script src="<?=base_url('assets/');?>js/examples/vmap.js"></script>

    <script src="<?=base_url('assets/');?>vendors/dataTable/datatables.min.js"></script>
    <script src="<?=base_url('assets/');?>js/examples/datatable.js"></script>

    <!-- To use theme colors with Javascript -->
    <div class="colors">
        <div class="bg-primary"></div>
        <div class="bg-primary-bright"></div>
        <div class="bg-secondary"></div>
        <div class="bg-secondary-bright"></div>
        <div class="bg-info"></div>
        <div class="bg-info-bright"></div>
        <div class="bg-success"></div>
        <div class="bg-success-bright"></div>
        <div class="bg-danger"></div>
        <div class="bg-danger-bright"></div>
        <div class="bg-warning"></div>
        <div class="bg-warning-bright"></div>
    </div>

    <script src="<?=base_url('assets/');?>/js/examples/pages/ecommerce-dashboard.js"></script>

<script src="<?=base_url('assets/');?>vendors/select2/js/select2.min.js"></script>
<script>
    $('.select2-example').select2({
    placeholder: 'Select'
});
</script>
<!-- App scripts -->
<script src="<?=base_url('assets/');?>js/app.min.js"></script>
<?php if(!empty($this->session->flashdata('success'))){ ?>
<script>
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
    toastr.success('<?=$this->session->flashdata('success');?>');
    $('.theme-switcher').removeClass('open');
}, 500);
</script>
<?php }elseif(!empty($this->session->flashdata('error'))){ ?>
<script>
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
    toastr.error('<?=$this->session->flashdata('error');?>');
    $('.theme-switcher').removeClass('open');
}, 500);
</script>
<?php } ?>
</body>
</html>
