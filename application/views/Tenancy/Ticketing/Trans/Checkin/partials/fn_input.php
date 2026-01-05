<?php start_section('page-script-fn-input'); ?>
    <script>
        var fn_input_type = <?php echo json_encode($input_type); ?>;
        var fn_result = <?php echo json_encode($result); ?>;
        var fn_detail = <?php echo json_encode($detail); ?>;
        var fn_building = <?php echo json_encode($building); ?>;
        var fn_venue = <?php echo json_encode($venue); ?>;
        var fn_counter = <?php echo json_encode($counter); ?>;
        var fn_tax = <?php echo json_encode($tax); ?>;
        var fn_method = <?php echo json_encode($method); ?>;
        var fn_building_code = <?php echo json_encode($building_code); ?>;
        var fn_venue_code = <?php echo json_encode($venue_code); ?>;
        var fn_counter_code = <?php echo json_encode($counter_code); ?>;

        var fn_i_date_trans = new Date();
        var fn_i_building_id = fn_building ? fn_building.id : null;
        var fn_i_venue_id = fn_venue ? fn_venue.id : null;
        var fn_i_counter_id = fn_counter ? fn_counter.id : null;

        var fn_i_qty = 0;
        var fn_i_total = 0;
        var fn_i_discount = 0;
        var fn_i_tax = fn_tax ? fn_tax.tax : 0;
        var fn_i_payment = null;
        var fn_i_is_payment = null;
        var fn_i_method = 'CASH';
        var fn_i_party_id = 0;
        var fn_i_order_id = null;
        var fn_i_note = null;

        var old = <?= json_encode($old ?? null); ?>;

        init_input();
        function init_input() {
            if (old) {
                fn_i_date_trans = new Date(<?php echo json_encode(old('date_trans')); ?>);
                fn_i_building_id = <?php echo json_encode(old('building_id')); ?>;
                fn_i_venue_id = <?php echo json_encode(old('venue_id')); ?>;
                fn_i_counter_id = <?php echo json_encode(old('counter_id')); ?>;
                
                fn_i_qty = <?php echo json_encode(old('qty')); ?>;
                fn_i_total = <?php echo json_encode(old('total')); ?>;
                fn_i_discount = <?php echo json_encode(old('discount')); ?>;
                fn_i_tax = <?php echo json_encode(old('tax')); ?>;
                fn_i_payment = <?php echo json_encode(old('payment')); ?>;
                fn_i_is_payment = <?php echo json_encode(old('is_payment')); ?>;

                fn_i_method = <?php echo json_encode(old('method')); ?>;
                fn_i_party_id = <?php echo json_encode(old('party_id')); ?>;
                fn_i_order_id = <?php echo json_encode(old('order_id')); ?>;
                fn_i_note = <?php echo json_encode(old('note')); ?>;

                fn_detail = <?php echo json_encode(old('ticket')); ?>;
            } else if (fn_input_type == 'edit') {
                fn_i_date_trans = new Date(fn_result.date_trans);
                fn_i_building_id = fn_result.building_id;
                fn_i_venue_id = fn_result.vanue_id;
                fn_i_counter_id = fn_result.counter_id;

                fn_i_qty = fn_result.qty;
                fn_i_total = fn_result.total;
                fn_i_discount = fn_result.discount;
                fn_i_tax = fn_result.tax;
                fn_i_payment = fn_result.payment;
                
                fn_i_method = fn_result.methode;
                fn_i_party_id = fn_result.party_id;
                fn_i_order_id = fn_result.order_id;
                fn_i_note = fn_result.note;
            }
        }
    </script>
<?php end_section('page-script-fn-input'); ?>