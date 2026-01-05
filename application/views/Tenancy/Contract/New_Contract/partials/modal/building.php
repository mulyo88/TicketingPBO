<?php start_section('view_modal_building'); ?>
    <section class="content-modal-building">
        <div class="modal fade" id="modal-building">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Building</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tbl-building" class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center text-capitalize">code</th>
                                        <th class="text-center text-capitalize">name</th>
                                        <th class="text-center text-capitalize">active</th>
                                        <th class="text-center text-capitalize"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($building as $row): ?>
                                        <tr>
                                            <td class="text-capitalize text-center"><?= $row->code ?></td>
                                            <td class="text-capitalize"><?= $row->name ?></td>
                                            <td class="text-capitalize text-center">
                                                <input type="checkbox"
                                                    <?php echo $row->is_active == 1 ? 'checked' : ''; ?>
                                                disabled>
                                            </td>

                                            <td class="text-center">
                                                <a href="#" class="label" style="background-color:black; color:white;"
                                                    onclick="selectBuilding('<?= $row->id ?>', '<?= $row->name ?>', '<?= $row->operation_time ?>')"
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
<?php end_section('view_modal_building'); ?>

<?php start_section('script_modal_building'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tbl-building').DataTable();
        });

        function selectBuilding(id, name, operation_time) {
            document.getElementById("building_id").value = id;
            document.getElementById("building_name").value = name;
            document.getElementById("building_operation").value = operation_time;
        }
    </script>
<?php end_section('script_modal_building'); ?>