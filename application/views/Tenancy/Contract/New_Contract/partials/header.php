<?php start_section('view_header'); ?>
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">trans</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" class="form-control pull-right datepicker" id="date_trans" name="date_trans" placeholder="dd-MMM-yyyy" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">building</label>
                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: end;">
                            <input type="hidden" class="form-control" id="building_id" name="building_id">
                            <input type="hidden" class="form-control" id="building_operation" name="building_operation">
                            <input type="text" class="form-control" id="building_name" name="building_name" placeholder="Building"
                                <?= invalid('building_name') ?>
                                readonly required
                            >

                            <button type="button" class="btn" style="margin-left: 5px; background-color:black; color:white; margin-right: 5px;"
                                data-toggle="modal" data-target="#modal-building"
                            >
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <?= error('building_name') ?>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">tenant</label>
                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: end;">
                            <input type="hidden" class="form-control" id="tenant_id" name="tenant_id">
                            <input type="hidden" class="form-control" id="tenant_code" name="tenant_code">
                            <input type="text" class="form-control" id="tenant_name" name="tenant_name" placeholder="Tenant"
                                <?= invalid('tenant_name') ?>
                                readonly required
                            >

                            <button type="button" class="btn" style="margin-left: 5px; background-color:black; color:white; margin-right: 5px;"
                                data-toggle="modal" data-target="#modal-tenant"
                            >
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <?= error('tenant_name') ?>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">bank transfer</label>
                        <div style="display: flex; flex-direction: row; align-items: center; justify-content: end;">
                            <input type="hidden" class="form-control" id="bank_id" name="bank_id">
                            <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Transfer"
                                readonly required
                                <?= invalid('bank_name') ?>
                            >

                            <button type="button" class="btn" style="margin-left: 5px; background-color:black; color:white; margin-right: 5px;"
                                data-toggle="modal" data-target="#modal-bank"
                            >
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                        <?= error('bank_name') ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">periode from</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            
                            <input type="text" class="form-control pull-right datepicker" id="tenant_start_date_operation" name="tenant_start_date_operation" placeholder="dd-MMM-yyyy" required
                                onchange="set_period()"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">periode to</label>
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>

                            <input type="text" class="form-control pull-right datepicker" id="tenant_end_date_operation" name="tenant_end_date_operation" placeholder="dd-MMM-yyyy" required
                                onchange="set_period()"
                            >
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">operation from</label>
                        <div class="input-group">
                            <input type="text" class="form-control timepicker" id="tenant_start_hour_operation" name="tenant_start_hour_operation" required>

                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">operation to</label>
                        <div class="input-group">
                            <input type="text" class="form-control timepicker" id="tenant_end_hour_operation" name="tenant_end_hour_operation" required>

                            <div class="input-group-addon">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php end_section('view_header'); ?>

<?php start_section('script_header'); ?>
    <script type="text/javascript">
        init_header();
        $(document).ready(function(){
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-M-yyyy'
            });

            $('.timepicker').timepicker({
                showInputs: false
            });
        });

        function init_header() {
            document.getElementById("date_trans").value = moment(fn_date_trans).format('DD-MMM-YYYY');
            document.getElementById("tenant_start_date_operation").value = moment(fn_tenant_start_date_operation).format('DD-MMM-YYYY');
            document.getElementById("tenant_end_date_operation").value = moment(fn_tenant_end_date_operation).format('DD-MMM-YYYY');
            document.getElementById("tenant_start_hour_operation").value = moment(fn_tenant_start_hour_operation).format('LT');
            document.getElementById("tenant_end_hour_operation").value = moment(fn_tenant_end_hour_operation).format('LT');
            document.getElementById("building_id").value = fn_building_id;
            document.getElementById("building_name").value = fn_building_name;
            document.getElementById("building_operation").value = fn_building_operation_time;
            document.getElementById("tenant_id").value = fn_tenant_id;
            document.getElementById("tenant_code").value = fn_tenant_code;
            document.getElementById("tenant_name").value = fn_tenant_name;
            document.getElementById("bank_id").value = fn_bank_id;
            document.getElementById("bank_name").value = fn_bank_name;
        }
    </script>
<?php end_section('script_header'); ?>