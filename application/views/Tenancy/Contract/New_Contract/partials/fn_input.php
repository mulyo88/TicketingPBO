<?php start_section('script_fn_input'); ?>
    <script type="text/javascript">
        var fn_input_type = <?php echo json_encode($input_type); ?>;
        var fn_result = <?php echo json_encode($result); ?>;

        var fn_tax = <?php echo json_encode($tax); ?>;

        var fn_date_trans = new Date();
        var fn_building_id = null;
        var fn_building_name = null;
        var fn_building_operation_time = 0;
        var fn_tenant_id = null;
        var fn_tenant_code = null;
        var fn_tenant_name = null;
        var fn_letter_from_id = null;
        var fn_letter_to_id = null;
        var fn_unit_qty = formatDecimal(0, 0);
        var fn_unit_total_per_item = formatNumber(0, 2);
        var fn_unit_total_per_month = formatNumber(0, 2);
        var fn_unit_total_per_grand = formatNumber(0, 2);
        var fn_charge_total_per_item = formatNumber(0, 2);
        var fn_charge_total_per_month = formatNumber(0, 2);
        var fn_charge_total_per_grand = formatNumber(0, 2);
        var fn_without_tax_total_per_item = formatNumber(0, 2);
        var fn_without_tax_total_per_month = formatNumber(0, 2);
        var fn_without_tax_total_per_grand = formatNumber(0, 2);
        var fn_with_tax_total_per_item = formatNumber(0, 2);
        var fn_with_tax_total_per_month = formatNumber(0, 2);
        var fn_with_tax_total_per_grand = formatNumber(0, 2);
        var fn_tax = formatDecimal(fn_tax ? fn_tax.tax : 0, 2);
        var fn_total_indoor = formatNumber(0, 2);
        var fn_total_outdoor = formatNumber(0, 2);
        var fn_charge_indoor = formatNumber(0, 2);
        var fn_charge_outdoor = formatNumber(0, 2);
        var fn_down_payment = formatDecimal(0, 2);
        var fn_security_deposite = formatDecimal(0, 2);
        var fn_fitting_out = formatDecimal(0, 2);
        var fn_month_period = formatDecimal(0, 0);
        var fn_tenant_start_date_operation = new Date();
        var fn_tenant_end_date_operation = new Date();
        var fn_building_operation = formatDecimal(0, 0);
        var fn_tenant_start_hour_operation = new Date();
        var fn_tenant_end_hour_operation = new Date();
        var fn_bank_id = null;
        var fn_bank_name = null;
        var fn_note = null;
        var fn_is_active = null;

        var fn_detail = null;
        var old = <?php echo json_encode(old('idx_token')); ?>;

        init_input();
        function init_input() {
            if (old) {
                fn_date_trans = new Date(<?php echo json_encode(old('date_trans')); ?>);
                fn_building_id = <?php echo json_encode(old('building_id')); ?>;
                fn_building_name = <?php echo json_encode(old('building_name')); ?>;
                fn_tenant_id = <?php echo json_encode(old('tenant_id')); ?>;
                fn_tenant_code = <?php echo json_encode(old('tenant_code')); ?>;
                fn_tenant_name = <?php echo json_encode(old('tenant_name')); ?>;
                fn_letter_from_id = <?php echo json_encode(old('letter_from_id')); ?>;
                fn_letter_to_id = <?php echo json_encode(old('letter_to_id')); ?>;
                fn_unit_qty = formatDecimal(<?php echo json_encode(old('unit_qty')); ?>, 0);
                fn_unit_total_per_item = formatNumber(<?php echo json_encode(old('unit_total_per_item')); ?>, 2);
                fn_unit_total_per_month = formatNumber(<?php echo json_encode(old('unit_total_per_month')); ?>, 2);
                fn_unit_total_per_grand = formatNumber(<?php echo json_encode(old('unit_total_per_grand')); ?>, 2);
                fn_charge_total_per_item = formatNumber(<?php echo json_encode(old('charge_total_per_item')); ?>, 2);
                fn_charge_total_per_month = formatNumber(<?php echo json_encode(old('charge_total_per_month')); ?>, 2);
                fn_charge_total_per_grand = formatNumber(<?php echo json_encode(old('charge_total_per_grand')); ?>, 2);
                fn_without_tax_total_per_item = formatNumber(<?php echo json_encode(old('without_tax_total_per_item')); ?>, 2);
                fn_without_tax_total_per_month = formatNumber(<?php echo json_encode(old('without_tax_total_per_month')); ?>, 2);
                fn_without_tax_total_per_grand = formatNumber(<?php echo json_encode(old('without_tax_total_per_grand')); ?>, 2);
                fn_with_tax_total_per_item = formatNumber(<?php echo json_encode(old('with_tax_total_per_item')); ?>, 2);
                fn_with_tax_total_per_month = formatNumber(<?php echo json_encode(old('with_tax_total_per_month')); ?>, 2);
                fn_with_tax_total_per_grand = formatNumber(<?php echo json_encode(old('with_tax_total_per_grand')); ?>, 2);
                fn_tax = formatDecimal(<?php echo json_encode(old('tax')); ?>, 2);
                fn_total_indoor = formatNumber(<?php echo json_encode(old('total_indoor')); ?>, 2);
                fn_total_outdoor = formatNumber(<?php echo json_encode(old('total_outdoor')); ?>, 2);
                fn_charge_indoor = formatNumber(<?php echo json_encode(old('charge_indoor')); ?>, 2);
                fn_charge_outdoor = formatNumber(<?php echo json_encode(old('charge_outdoor')); ?>, 2);
                fn_down_payment = formatDecimal(<?php echo json_encode(old('down_payment')); ?>, 2);
                fn_security_deposite = formatDecimal(<?php echo json_encode(old('security_deposite')); ?>, 2);
                fn_fitting_out = formatDecimal(<?php echo json_encode(old('fitting_out')); ?>, 2);
                fn_month_period = formatDecimal(<?php echo json_encode(old('month_period')); ?>, 0);
                fn_tenant_start_date_operation = new Date(<?php echo json_encode(old('tenant_start_date_operation')); ?>);
                fn_tenant_end_date_operation = new Date(<?php echo json_encode(old('tenant_end_date_operation')); ?>);
                fn_building_operation = formatDecimal(<?php echo json_encode(old('building_operation')); ?>, 0);
                fn_tenant_start_hour_operation = new Date(setTimeWithDate(<?php echo json_encode(old('tenant_start_hour_operation')); ?>));
                fn_tenant_end_hour_operation = new Date(setTimeWithDate(<?php echo json_encode(old('tenant_end_hour_operation')); ?>));
                fn_bank_id = <?php echo json_encode(old('bank_id')); ?>;
                fn_bank_name = <?php echo json_encode(old('bank_name')); ?>;
                fn_note = <?php echo json_encode(old('note')); ?>;
                fn_is_active = <?php echo json_encode(old('is_active')); ?>;

                fn_detail = <?php echo json_encode(old('unit')); ?>;
            
            } else if (fn_input_type == 'edit') {
                fn_date_trans = new Date(fn_result.date_trans);
                fn_building_id = fn_result.building_id;
                fn_building_name = fn_result.building ? fn_result.building.name : null;
                fn_building_operation_time = fn_result.building ? fn_result.building.operation_time : 0;
                fn_tenant_id = fn_result.tenant_id;
                fn_tenant_code = fn_result.tenant ? fn_result.tenant.code : null;
                fn_tenant_name = fn_result.tenant ? fn_result.tenant.owner : null;
                fn_letter_from_id = fn_result.letter_from_id;
                fn_letter_to_id = fn_result.letter_to_id;
                fn_unit_qty = formatDecimal(fn_result.unit_qty, 0);
                fn_unit_total_per_item = formatNumber(fn_result.unit_total_per_item, 2);
                fn_unit_total_per_month = formatNumber(fn_result.unit_total_per_month, 2);
                fn_unit_total_per_grand = formatNumber(fn_result.unit_total_per_grand, 2);
                fn_charge_total_per_item = formatNumber(fn_result.charge_total_per_item, 2);
                fn_charge_total_per_month = formatNumber(fn_result.charge_total_per_month, 2);
                fn_charge_total_per_grand = formatNumber(fn_result.charge_total_per_grand, 2);
                fn_without_tax_total_per_item = formatNumber(fn_result.without_tax_total_per_item, 2);
                fn_without_tax_total_per_month = formatNumber(fn_result.without_tax_total_per_month, 2);
                fn_without_tax_total_per_grand = formatNumber(fn_result.without_tax_total_per_grand, 2);
                fn_with_tax_total_per_item = formatNumber(fn_result.with_tax_total_per_item, 2);
                fn_with_tax_total_per_month = formatNumber(fn_result.with_tax_total_per_month, 2);
                fn_with_tax_total_per_grand = formatNumber(fn_result.with_tax_total_per_grand, 2);
                fn_tax = formatDecimal(fn_result.tax, 2);
                fn_total_indoor = formatNumber(fn_result.total_indoor, 2);
                fn_total_outdoor = formatNumber(fn_result.total_outdoor, 2);
                fn_charge_indoor = formatNumber(fn_result.charge_indoor, 2);
                fn_charge_outdoor = formatNumber(fn_result.charge_outdoor, 2);
                fn_down_payment = formatDecimal(fn_result.down_payment, 2);
                fn_security_deposite = formatDecimal(fn_result.security_deposite, 2);
                fn_fitting_out = formatDecimal(fn_result.fitting_out, 2);
                fn_month_period = formatDecimal(fn_result.month_period, 0);
                fn_tenant_start_date_operation = new Date(fn_result.tenant_start_date_operation);
                fn_tenant_end_date_operation = new Date(fn_result.tenant_end_date_operation);
                fn_building_operation = formatDecimal(fn_result.building_operation, 0);
                fn_tenant_start_hour_operation = new Date(fn_result.tenant_start_hour_operation);
                fn_tenant_end_hour_operation = new Date(fn_result.tenant_end_hour_operation);
                fn_bank_id = fn_result.bank_id;
                fn_bank_name = fn_result.bank ? fn_result.bank.account_name : null;
                fn_note = fn_result.note;
                fn_is_active = fn_result.is_active;

                fn_detail = fn_result.details;
            }
        }
    </script>
<?php end_section('script_fn_input'); ?>