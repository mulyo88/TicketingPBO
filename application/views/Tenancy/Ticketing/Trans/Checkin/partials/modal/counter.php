<?php start_section('content-modal-counter'); ?>
    <div class="modal fade" id="modal-counter">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Counter</h4>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table id="tbl_counter" class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center text-capitalize">area</th>
                                    <th class="text-center text-capitalize">location</th>
                                    <th class="text-center text-capitalize">counter</th>
                                    <th class="text-center text-capitalize"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($counters as $row): ?>
                                    <tr>
                                        <td class="text-capitalize"><?= ($row->venue ? ($row->venue->building ? $row->venue->building->code : '') : '') . ' - ' . ($row->venue ? ($row->venue->building ? $row->venue->building->name : '') : '') ?></td>
                                        <td class="text-capitalize"><?= ($row->venue ? $row->venue->code : '') . ' - ' .  ($row->venue ? $row->venue->name : '') ?></td>
                                        <td class="text-capitalize"><?= $row->code ?></td>
                                        <td class="text-center">
                                            <a href="#" class="label" style="background-color:black; color:white;"
                                                onclick="location.href='<?=site_url('Tenancy/Ticketing/Trans/Checkin/create/'.($row->venue ? ($row->venue->building ? $row->venue->building->code : 'none') : 'none').'/'.($row->venue ? $row->venue->code : 'none').'/'.$row->code)?>'"
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
<?php end_section('content-modal-counter'); ?>

<?php start_section('script-content-modal-counter'); ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#tbl_counter').DataTable();
        });
    </script>
<?php end_section('script-content-modal-counter'); ?>