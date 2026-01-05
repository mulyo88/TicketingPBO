<section class="content-modal-unit">
    <div class="modal fade" id="modal-unit">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Unit Types</h4>
                </div>
                <div class="modal-body">
                    <input type="text" class="form-control" id="type_modal_unit" name="type_modal_unit" placeholder="Type">

                    <div class="table-responsive">
                        <table id="tbl-unit" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center text-capitalize">building</th>
                                    <th class="text-center text-capitalize">code</th>
                                    <th class="text-center text-capitalize">name</th>
                                    <th class="text-center text-capitalize">size</th>
                                    <th class="text-center text-capitalize">basic price</th>
                                    <th class="text-center text-capitalize">active</th>
                                    <th class="text-center text-capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unit as $row): ?>
                                    <tr>
                                        <td class="text-capitalize"><?= $row->building_name ?></td>
                                        <td class="text-capitalize text-center"><?= $row->code ?></td>
                                        <td class="text-capitalize"><?= $row->unit_name ?></td>
                                        <td class="text-capitalize"><?= $row->size ?></td>
                                        <td class="text-capitalize text-right"><?= number_format($row->basic_price, 2, '.', ',') ?></td>
                                        <td class="text-capitalize text-center">
                                            <input type="checkbox"
                                                <?php echo $row->is_active == 1 ? 'checked' : ''; ?>
                                            disabled>
                                        </td>

                                        <td class="text-center">
                                            <a href="#" class="label" style="background-color:black; color:white;"
                                                onclick="selectUnit('<?= $row->id ?>')"
                                                data-dismiss="modal"
                                            >
                                                <span class="text-capitalize">select</span>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</section>

<script type="text/javascript">
    $(document).ready(function(){
        $('#tbl-unit').DataTable();
    });

    function openUnitCopy(type) {
        document.getElementById("type_modal_unit").value = type;
    }

    function selectUnit(id) {
        var type = document.getElementById("type_modal_unit").value;

        if (type == "facility") {
            clearFacility();
        } else if (type == "owner") {
            clearOwner();
        }

        $.ajax({
            dataType: "json",
            type: "GET",
            url: "<?= site_url('Tenancy/API/GlobalAPI_MasterData/select_unit_by_id') ?>/" + id + "/" + type,

            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(data) {
                if (data) {
                    for (let index = 0; index < data.length; index++) {
                        const element = data[index];
                        
                        if (type == "facility") {
                            selectFacility(data[index].unit_facility_id, data[index].name, data[index].qty);
                        } else if (type == "owner") {
                            selectOwner(data[index].owner_property_id, data[index].name);
                        }
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