<?php start_section('page-script-fn-input'); ?>
    <script>
        var fn_input_type = <?php echo json_encode($input_type); ?>;
        var fn_result = <?php echo json_encode($result); ?>;
        var fn_building = <?php echo json_encode($building); ?>;
        var fn_venue = <?php echo json_encode($venue); ?>;
        var fn_gate = <?php echo json_encode($gate); ?>;
        var fn_building_code = <?php echo json_encode($building_code); ?>;
        var fn_venue_code = <?php echo json_encode($venue_code); ?>;
        var fn_gate_code = <?php echo json_encode($gate_code); ?>;

        var fn_i_date_trans = new Date();
        var fn_i_building_id = fn_building ? fn_building.id : null;
        var fn_i_venue_id = fn_venue ? fn_venue.id : null;
        var fn_i_gate_id = fn_gate ? fn_gate.id : null;

        var old = <?= json_encode($old ?? null); ?>;

        init_input();
        function init_input() {
            if (old) {
                fn_i_date_trans = new Date(<?php echo json_encode(old('date_trans')); ?>);
                fn_i_building_id = <?php echo json_encode(old('building_id')); ?>;
                fn_i_venue_id = <?php echo json_encode(old('venue_id')); ?>;
                fn_i_gate_id = <?php echo json_encode(old('gate_id')); ?>;
            } else if (fn_input_type == 'edit') {
                fn_i_date_trans = new Date(fn_result.date_trans);
                fn_i_building_id = fn_result.building_id;
                fn_i_venue_id = fn_result.vanue_id;
                fn_i_gate_id = fn_result.gate_id;
            }
        }
    </script>
<?php end_section('page-script-fn-input'); ?>