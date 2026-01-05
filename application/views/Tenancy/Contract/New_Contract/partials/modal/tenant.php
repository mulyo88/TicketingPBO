<?php start_section('view_modal_tenant'); ?>
    <section class="content-modal-tenant">
        <div class="modal fade" id="modal-tenant">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tenant</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tbl-tenant" class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center text-capitalize">code</th>
                                        <th class="text-center text-capitalize">owner</th>
                                        <th class="text-center text-capitalize">brand</th>
                                        <th class="text-center text-capitalize">product</th>
                                        <th class="text-center text-capitalize">active</th>
                                        <th class="text-center text-capitalize"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tenant as $row): ?>
                                        <tr>
                                            <td class="text-capitalize text-center"><?= $row->code ?></td>
                                            <td class="text-capitalize"><?= $row->owner ?></td>
                                            <td class="text-capitalize"><?= $row->brand ?></td>
                                            <td class="text-capitalize"><?= $row->product ?></td>
                                            <td class="text-capitalize text-center">
                                                <input type="checkbox"
                                                    <?php echo $row->is_active == 1 ? 'checked' : ''; ?>
                                                disabled>
                                            </td>

                                            <td class="text-center">
                                                <a href="#" class="label" style="background-color:black; color:white;"
                                                    onclick="selectTenant('<?= $row->id ?>', '<?= $row->code ?>', '<?= $row->owner ?>', '<?= $row->brand ?>', '<?= $row->product ?>')"
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
<?php end_section('view_modal_tenant'); ?>

<?php start_section('script_modal_tenant'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tbl-tenant').DataTable();
        });

        function selectTenant(id, code, owner, brand, product) {
            document.getElementById("tenant_id").value = id;
            document.getElementById("tenant_code").value = code;
            document.getElementById("tenant_name").value = owner;
            document.getElementById("tenant_owner").innerHTML = owner;
            document.getElementById("tenant_brand").innerHTML = brand;
            document.getElementById("tenant_product").innerHTML = product;
        }
    </script>
<?php end_section('script_modal_tenant'); ?>