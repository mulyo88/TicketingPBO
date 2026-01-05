<section class="content-modal-owner">
    <div class="modal fade" id="modal-owner">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Owner Property</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbl-owner" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center text-capitalize">name</th>
                                    <th class="text-center text-capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($owner as $row): ?>
                                    <tr>
                                        <td class="text-capitalize"><?= $row->name ?></td>
                                        <td class="text-center">
                                            <a href="#" class="label" style="background-color:black; color:white;"
                                                onclick="selectOwner('<?= $row->id ?>', '<?= $row->name ?>')"
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
        $('#tbl-owner').DataTable();
    });
</script>