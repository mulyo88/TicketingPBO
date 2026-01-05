<?php start_section('page-content-header'); ?>
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
                        <label class="text-capitalize">area/building</label>
                        <select name="building_code" id="building_code" class="form-control"
                            onchange="select_building_code();"
                            <?= invalid('building_code') ?>
                        >
                            <?php foreach ($building as $row): ?>
                                <option value="<?=$row->code?>" data-area="<?=$row->code?>" <?php old('building_code') == $row->id ? 'selected' : '' ?>><?=$row->code?> - <?=$row->name?></option>
                            <?php endforeach ?>
                        </select>
                        <?= error('building_code') ?>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6"></div>
                <div class="col-md-3 col-xs-6"></div>
            </div>
        </div>
    </div>
<?php end_section('page-content-header'); ?>

<?php start_section('page-script-header'); ?>
    <script>
        init_header();
        $(document).ready(function(){
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-M-yyyy'
            });
        });

        function init_header() {
            document.getElementById("date_trans").value = moment(fn_i_date_trans).format('DD-MMM-YYYY');
            document.getElementById("building_code").value = fn_i_building_code;
        }

        function select_building_code() {
            reset_item();
        }
    </script>
<?php end_section('page-script-header'); ?>
