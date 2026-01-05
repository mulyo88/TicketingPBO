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
                        <label class="text-capitalize">from: party type</label>
                        <select name="from_type" id="from_type" class="form-control"
                            onchange="load_party_from(this.value);"
                            <?= invalid('from_type') ?>
                        >
                            <?php foreach ($from_type as $row): ?>
                                <option value="<?=$row->id?>" <?php old('from_type') == $row->id ? 'selected' : '' ?>><?=$row->name?></option>
                            <?php endforeach ?>
                        </select>
                        <?= error('from_type') ?>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">party from</label>
                        <select name="from_id" id="from_id" class="form-control"
                            <?= invalid('from_id') ?>
                        >
                        </select>
                        <?= error('from_id') ?>
                    </div>
                </div>

                <div class="col-md-3 col-xs-6">
                    <div class="form-group">
                        <label class="text-capitalize">to: area/building</label>
                        <select name="building_id" id="building_id" class="form-control"
                            onchange="select_building_id();"
                            <?= invalid('building_id') ?>
                        >
                            <?php foreach ($building as $row): ?>
                                <option value="<?=$row->id?>" data-area="<?=$row->code?>" <?php old('building_id') == $row->id ? 'selected' : '' ?>><?=$row->code?> - <?=$row->name?></option>
                            <?php endforeach ?>
                        </select>
                        <?= error('building_id') ?>
                    </div>
                </div>
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
            document.getElementById("building_id").value = fn_i_building_id;
            document.getElementById("from_type").value = fn_i_from_type;
            if (fn_i_from_type) { load_party_from(fn_i_from_type); }
        }

        function select_building_id() {
            reset_item();
        }

        function load_party_from(value) {
            document.getElementById("from_id").innerHTML = '';

            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/load_party') ?>/" + value,

                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(data) {
                    if (data ) {
                        if (data.common.name == "DEPARTEMENT") {
                            data.party.forEach(function(item) {
                                var option = document.createElement("option");
                                option.value = item.id;
                                option.text = item.code + ' - ' + item.name;
                                if (fn_i_from_id == item.id) { option.selected = true; }
                                document.getElementById("from_id").appendChild(option);
                            });
                        } else if (data.common.name == "SUPPLIER") {
                            data.party.forEach(function(item) {
                                var option = document.createElement("option");
                                option.value = item.id;
                                option.text = item.name;
                                if (fn_i_from_id == item.id) { option.selected = true; }
                                document.getElementById("from_id").appendChild(option);
                            });
                        } else if (data.common.name == "CUSTOMER") {
                            data.party.forEach(function(item) {
                                var option = document.createElement("option");
                                option.value = item.id;
                                option.text = item.name;
                                if (fn_i_from_id == item.id) { option.selected = true; }
                                document.getElementById("from_id").appendChild(option);
                            });
                        } else if (data.common.name == "BUILDING") {
                            data.party.forEach(function(item) {
                                var option = document.createElement("option");
                                option.value = item.id;
                                option.text = item.code + ' - ' + item.name;
                                if (fn_i_from_id == item.id) { option.selected = true; }
                                document.getElementById("from_id").appendChild(option);
                            });
                        }
                    }
                },
                error: function(xhr, status, error) {
                    alert(error);
                    return;
                }
            });
        }
    </script>
<?php end_section('page-script-header'); ?>
