<?php start_section('page-script-fn-input'); ?>
    <script>
        var fn_detail = null;
        var fn_building = <?php echo json_encode($building); ?>;
        var fn_status = <?php echo json_encode($status); ?>;

        var fn_i_date_trans = new Date();
        var fn_i_building_code = null;

        var old = <?php echo json_encode($this->session->flashdata('_old_input') ?? null); ?>;
        
        init_input();
        function init_input() {
            if (old) {
                fn_i_date_trans = new Date(<?php echo json_encode(old('date_trans')); ?>);
                fn_i_building_code = <?php echo json_encode(old('building_code')); ?>;

                fn_detail = <?php echo json_encode(old('item')); ?>;
            }
        }
    </script>
<?php end_section('page-script-fn-input'); ?>