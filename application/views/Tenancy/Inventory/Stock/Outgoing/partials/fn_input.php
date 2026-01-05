<?php start_section('page-script-fn-input'); ?>
    <script>
        var fn_input_type = <?php echo json_encode($input_type); ?>;
        var fn_result = <?php echo json_encode($result); ?>;
        var fn_detail = <?php echo json_encode($detail); ?>;
        var fn_building = <?php echo json_encode($building); ?>;
        var fn_to_type = <?php echo json_encode($to_type); ?>;
        var fn_to_type_common = <?php echo json_encode($to_type_common); ?>;

        var fn_i_date_trans = new Date();
        var fn_i_building_id = null;
        var fn_i_to_type = null;

        var fn_i_to_id = null;
        var fn_i_qty = 0;
        var fn_i_note = null;

        var old = <?php echo json_encode($this->session->flashdata('_old_input') ?? null); ?>;
        
        init_input();
        function init_input() {
            if (old) {
                fn_i_date_trans = new Date(<?php echo json_encode(old('date_trans')); ?>);
                fn_i_building_id = <?php echo json_encode(old('building_id')); ?>;
                fn_i_to_type = <?php echo json_encode(old('to_type')); ?>;
                fn_i_to_id = <?php echo json_encode(old('to_id')); ?>;
                fn_i_qty = <?php echo json_encode(old('qty')); ?>;
                fn_i_note = <?php echo json_encode(old('note')); ?>;

                fn_detail = <?php echo json_encode(old('item')); ?>;
            } else if (fn_input_type == 'edit') {
                fn_i_date_trans = new Date(fn_result.date_trans);
                fn_i_building_id = fn_result.building_id;
                fn_i_to_type = fn_to_type_common ? fn_to_type_common.id : null;
                fn_i_to_id = fn_result.to_id;
                fn_i_qty = fn_result.qty;
                fn_i_note = fn_result.note;
            }
        }
    </script>
<?php end_section('page-script-fn-input'); ?>