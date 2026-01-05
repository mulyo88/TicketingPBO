<?php start_section('view_modal_unit'); ?>
    <section class="content-modal-unit">
        <div class="modal fade" id="modal-unit">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Unit Type</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tbl-unit" class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center text-capitalize">code</th>
                                        <th class="text-center text-capitalize">name</th>
                                        <th class="text-center text-capitalize">type</th>
                                        <th class="text-center text-capitalize">size</th>
                                        <th class="text-center text-capitalize">basic price</th>
                                        <th class="text-center text-capitalize">active</th>
                                        <th class="text-center text-capitalize"></th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
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
<?php end_section('view_modal_unit'); ?>

<?php start_section('script_modal_unit'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tbl-unit').DataTable();
        });
    </script>
<?php end_section('script_modal_unit'); ?>