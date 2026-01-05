<?php start_section('page-content-detail'); ?>
    <div class="panel">
        <div class="panel-body">
            <div class="box-body">
                <div class="form-group">
                    <div style="display: flex; flex-direction: row; align-items: center; justify-content: end;">
                        <input type="text" class="form-control" id="scan" placeholder="Scan barcode here..." onkeypress="press_scan(event)">
                        <button type="button" class="btn btn-info" style="margin-left: 5px;"
                            onclick="scan_barcode()"
                        >
                            <i class="fa fa-barcode"></i>
                        </button>
                        <button type="button" class="btn" style="margin-left: 5px; background-color:black; color:white;"
                            onclick="open_modal_item()"
                        >
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr style="background-color: black; color: white; font-weight:bold;">
                                <th class="text-center text-capitalize" style="vertical-align: middle;">items</th>
                                <th class="text-center text-capitalize" style="vertical-align: middle;">beginning</th>
                                <th class="text-center text-capitalize" style="vertical-align: middle;">qty</th>
                                <th class="text-center text-capitalize" style="vertical-align: middle;">ending</th>
                                <th class="text-center" style="vertical-align: middle;">UOM</th>
                                <th class="text-center text-capitalize" style="vertical-align: middle;">status</th>
                                <th class="text-center text-capitalize" style="vertical-align: middle;">note</th>
                                <th class="text-center text-capitalize" style="vertical-align: middle;">
                                    <button type="button" class="btn btn-danger" onclick="reset_item()"><i class="fa fa-trash"></i></button>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="tbody-item"></tbody>
                        <tfoot>
                            <tr style="background-color: #C7C5C5; color: black; font-weight:bold;">
                                <th class="text-center text-capitalize">total</th>
                                <th class="text-center text-capitalize"></th>
                                <th class="text-right text-capitalize">
                                    <input type="number" any="step" id="total_qty" name="total_qty" class="form-control" value="0" style="text-align: right;" readonly required
                                        <?= invalid('total_qty') ?>
                                    >
                                    <?= error('total_qty') ?>
                                </th>
                                <th class="text-center text-capitalize" colspan="5"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="box-footer" style="display:flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="margin-right: 5px;"><i class="fa fa-save" style="margin-right: 5px;"></i>Save</button>
                <button type="button" class="btn" style="background-color: black; color: white;"
                    onclick="location.href='<?=site_url('Tenancy/Inventory/Stock/Stock_Entry/index')?>'"
                >
                    <i class="fa fa-undo" style="margin-right: 5px;"></i>
                    Back to List
                </button>
            </div>
        </div>
    </div>
<?php end_section('page-content-detail'); ?>

<?php start_section('page-script-detail'); ?>
    <script>
        init_trans(fn_detail);
        function init_trans(data) {
            reset_item();

            if (fn_detail) {
                Object.values(fn_detail).forEach(item => {
                    selectItem(item, old ? 'old' : fn_input_type);
                });
            }
        }

        function press_scan(e) {
            if (e.keyCode === 13) { // where 13 is the enter button
                e.preventDefault(); //ignore submit

                scan_barcode();
            }
        }

        function scan_barcode() {
            var scanInput = document.getElementById("scan");
            var barcode = scanInput.value.trim();

            if (barcode === "") {
                return;
            }

            var building_code = document.getElementById("building_code");

            if (building_code.value == "") {
                alert("Please select area/building first.");
                return;
            }

            var selectedOption = building_code.options[building_code.selectedIndex];
            var area = selectedOption.getAttribute('data-area');

            $.ajax({
                dataType: "json",
                type: "GET",
                url: "<?= site_url('Tenancy/API/GlobalAPI_Trans/scan_barcode') ?>/" + barcode + "/" + area,

                beforeSend: function() {

                },
                complete: function() {

                },
                success: function(data) {
                    if (data) {
                        add_item(data);
                    } else {
                        var snd = new Audio(beep_warning());
                        snd.play();
                    }

                    scanInput.value = "";
                },
                error: function(xhr, status, error) {
                    alert(error);
                    return;
                }
            });
        }
    </script>
<?php end_section('page-script-detail'); ?>
