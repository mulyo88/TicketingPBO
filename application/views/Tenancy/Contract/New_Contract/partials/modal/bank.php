<?php start_section('view_modal_bank'); ?>
    <section class="content-modal-bank">
        <div class="modal fade" id="modal-bank">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Bank</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tbl-bank" class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center text-capitalize">account code</th>
                                        <th class="text-center text-capitalize">account name</th>
                                        <th class="text-center text-capitalize">owner name</th>
                                        <th class="text-center text-capitalize">office name</th>
                                        <th class="text-center text-capitalize">active</th>
                                        <th class="text-center text-capitalize"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bank as $row): ?>
                                        <tr>
                                            <td class="text-capitalize"><?= $row->account_code ?></td>
                                            <td class="text-capitalize"><?= $row->account_name ?></td>
                                            <td class="text-capitalize"><?= $row->owner_name ?></td>
                                            <td class="text-capitalize"><?= $row->office_name ?></td>
                                            <td class="text-capitalize text-center">
                                                <input type="checkbox"
                                                    <?php echo $row->is_active == 1 ? 'checked' : ''; ?>
                                                disabled>
                                            </td>

                                            <td class="text-center">
                                                <a href="#" class="label" style="background-color:black; color:white;"
                                                    onclick="selectBank('<?= $row->id ?>', '<?= $row->account_name ?>')"
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
<?php end_section('view_modal_bank'); ?>

<?php start_section('script_modal_bank'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tbl-bank').DataTable();
        });

        function selectBank(id, name) {
            document.getElementById("bank_id").value = id;
            document.getElementById("bank_name").value = name;
        }
    </script>
<?php end_section('script_modal_bank'); ?>