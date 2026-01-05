
<?php
    error_reporting(E_ALL);
?>

<style type="text/css">
	.marginatas5{
		margin-top: 5px;
	}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><?=($judul) ? $judul : 'Header not set'  ?></h1>
        <ol class="breadcrumb">

        </ol>
    </section>

    <section class="content">
        <?=($this->session->flashdata('message')) ? $this->session->flashdata('message') : '' ?>

        <div class="panel">
            <div class="panel-body">
                <button type="button" class="btn" style="background-color:black; color:white; margin-bottom: 10px;"
                    onclick="location.href='<?=site_url('Tenancy/Contract/New_Contract/create')?>'">
                    <i class="fa fa-plus"></i><span style="margin-left: 5px;">Create New</span>
                </button>

                <div class="table-responsive">
                    <table id="tbl" class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center text-capitalize">data</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $seq = 0; ?>
                            <?php foreach ($results as $row): ?>
                                <?php $seq += 1; ?>

                                <tr>
                                    <td>
                                        <div class="row text-left">
                                            <div class="col-md-6 col">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">tenant</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">: <?= $row->tenant ? $row->tenant->code : '' ?></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">owner</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">: <?= $row->tenant ? $row->tenant->owner : '' ?></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">brand</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">: <?= $row->tenant ? $row->tenant->brand : '' ?></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">product</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">: <?= $row->tenant ? $row->tenant->product : '' ?></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label">LOO Documents</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">: 
                                                            <?php if (count($loo_docs) > 0) { ?>
                                                                <span style="color: 
                                                                    <?php if (count($row->documents) / count($loo_docs) * 100 < 100) { ?>
                                                                        #F54927
                                                                    <?php } else { ?>
                                                                        #1AAB00
                                                                    <?php } ?>
                                                                ;">
                                                                    <?= number_format(count($row->documents) / count($loo_docs) * 100, 2, '.', ',') ?>%
                                                                </span>
                                                            <?php } else { ?>
                                                                0%
                                                            <?php } ?>

                                                            <a href="javascript:void(0);" class="label text-capitalize"
                                                                style="background-color: black; color: white;"
                                                                onclick="open_document(<?php echo $seq; ?>)"
                                                            >
                                                                <i class="fa fa-eye" style="margin-right: 5px;"></i>see detail
                                                            </a>

                                                            <div id="doc_<?php echo $seq; ?>" hidden>
                                                                <?php foreach ($loo_docs as $doc): ?>
                                                                    <div style="display: flex; flex-direction: row; margin-left: 10px;">
                                                                        <span>
                                                                            <?php if ($row->documents) { ?>
                                                                                <?php $have = false; $file = ''; ?>
                                                                                <?php foreach ($row->documents as $document): ?>
                                                                                    <?php if ($document->common_id == $doc->id) { ?>
                                                                                        <?php $have = true; $file = $document->file; ?>
                                                                                        <?php break; ?>
                                                                                    <?php } ?>
                                                                                <?php endforeach ?>

                                                                                <?php if ($have) { ?>
                                                                                    <a href="<?php echo base_url() .'/'.$this->config->item("tnc_tenant").'/'.$row->tenant_id.'/'.$file ?>" class="text-primary">
                                                                                        <?= $doc->name ?>
                                                                                    </a>

                                                                                    <i class="fa fa-check text-success"></i>
                                                                                <?php } else { ?>
                                                                                    <span><?= $doc->name ?></span>
                                                                                    <i class="fa fa-times text-danger"></i>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <span><?= $doc->name ?></span>
                                                                                <i class="fa fa-times text-danger"></i>
                                                                            <?php } ?>
                                                                        </span>
                                                                    </div>
                                                                <?php endforeach ?>
                                                            </div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col">
                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">series</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">: <?= $row->series ?></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">date</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label text-capitalize">: <?= date("d-M-Y", strtotime($row->date_trans)) ?></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">building</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">: <?= $row->building ? $row->building->name : '' ?></span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">period</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">: <?= date("d-M-Y", strtotime($row->tenant_start_date_operation)) ?> to <?= date("d-M-Y", strtotime($row->tenant_end_date_operation)) ?> (<?= number_format($row->month_period, 0, '.', ',') ?> month)</span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">unit</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span class="control-label">:
                                                            <?php foreach ($row->details as $detail): ?>
                                                                <?php if ($detail->unit) { ?>
                                                                    <?php if ($detail->type_item == 'Unit') { ?>
                                                                        <span class="label" style="margin-right: 2px; background-color: 
                                                                            <?php if ($detail->unit->unit_type == 'Indoor') { ?>
                                                                                #A200AB
                                                                            <?php } else { ?>
                                                                                #1AAB00
                                                                            <?php } ?>
                                                                        ;">
                                                                            <?= $detail->unit->unit_type ?>-<?= $detail->unit->name ?> <?= number_format($detail->unit_size, 0, '.', ',') ?>m<sup>2</sup>
                                                                        </span>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            <?php endforeach ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row rows_<?php echo $seq; ?>" hidden>
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">grandtotal</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span>: 
                                                            <?= number_format($row->with_tax_total_per_grand, 2, '.', ',') ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row rows_<?php echo $seq; ?>" hidden>
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">payment</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span>: 
                                                            <?= number_format((floatval($row->with_tax_total_per_grand) * floatval($row->down_payment) / 100) + floatval($row->security_deposite) + floatval($row->fitting_out), 2, '.', ',') ?>
                                                        </span>
                                                        <a href="javascript:void(0);" class="label text-capitalize"
                                                            style="background-color: black; color: white;"
                                                            onclick="open_payment(<?php echo $seq; ?>)"
                                                        >
                                                            <i class="fa fa-eye" style="margin-right: 5px;"></i>see detail
                                                        </a>
                                                    </div>
                                                </div>

                                                <div class="row pay_<?php echo $seq; ?>" hidden>
                                                    <div class="col-xs-3 text-right">
                                                        <span class="control-label">DP</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span>: 
                                                        <?= number_format(floatval($row->down_payment) * floatval($row->with_tax_total_per_grand) / 100, 2, '.', ',') ?> (<?= number_format($row->down_payment, 2, '.', ',') ?>%)
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row pay_<?php echo $seq; ?>" hidden>
                                                    <div class="col-xs-3 text-right">
                                                        <span class="control-label text-capitalize">deposite</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span>: 
                                                            <?= number_format($row->security_deposite, 2, '.', ',') ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row pay_<?php echo $seq; ?>" hidden>
                                                    <div class="col-xs-3 text-right">
                                                        <span class="control-label text-capitalize">fitting</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span>: 
                                                            <?= number_format($row->fitting_out, 2, '.', ',') ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row rows_<?php echo $seq; ?>" hidden>
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">balance</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span style="color: 
                                                            <?php if (
                                                                floatval(((floatval($row->with_tax_total_per_grand) * floatval($row->down_payment) / 100) + floatval($row->security_deposite) + floatval($row->fitting_out)) - floatval($row->with_tax_total_per_grand)) < 0
                                                            ) { ?>
                                                                #FF0000
                                                            <?php } else { ?>
                                                                #1AAB00
                                                            <?php } ?>
                                                        ;">: 
                                                            <?= number_format(((floatval($row->with_tax_total_per_grand) * floatval($row->down_payment) / 100) + floatval($row->security_deposite) + floatval($row->fitting_out)) - floatval($row->with_tax_total_per_grand), 2, '.', ',') ?>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-xs-3">
                                                        <span class="control-label text-capitalize">pay. prog.</span>
                                                    </div>
                                                    <div class="col-xs-9">
                                                        <span style="color: 
                                                            <?php if (
                                                                floatval(floatval($row->with_tax_total_per_grand) / floatval((floatval($row->with_tax_total_per_grand) * floatval($row->down_payment) / 100) + floatval($row->security_deposite) + floatval($row->fitting_out) * 100)) < 100
                                                            ) { ?>
                                                                #FF0000
                                                            <?php } else { ?>
                                                                #1AAB00
                                                            <?php } ?>
                                                        ;">: 
                                                            <?= number_format(floatval($row->with_tax_total_per_grand) / floatval((floatval($row->with_tax_total_per_grand) * floatval($row->down_payment) / 100) + floatval($row->security_deposite) + floatval($row->fitting_out) * 100), 2, '.', ',') ?>%
                                                        </span>
                                                        <a href="javascript:void(0);" class="label text-capitalize"
                                                            style="background-color: black; color: white;"
                                                            onclick="open_header(<?php echo $seq; ?>)"
                                                        >
                                                            <i class="fa fa-eye" style="margin-right: 5px;"></i>see detail
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row" id="tbl_sum_<?php echo $seq; ?>" hidden>
                                            <div class="col" style="padding-top: 15px; padding-left: 15px; padding-right: 15px;">
                                                <table class="table table-bordered table-sm">
                                                    <thead style="background-color:black; color:white;">
                                                        <tr>
                                                            <th class="text-center text-capitalize" style="padding:4px;">summary</th>
                                                            <th class="text-center text-capitalize" style="padding:4px; width:90px;">per items</th>
                                                            <th class="text-center text-capitalize" style="padding:4px; width:90px;">per month</th>
                                                            <th class="text-center text-capitalize" style="padding:4px; width:90px;">total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if ($row->details) { ?>
                                                            <?php foreach ($row->details as $detail): ?>
                                                                <tr>
                                                                    <td class="text-left">
                                                                        <?= $detail->type_item == 'Charge' ? 'Service Charge' : $detail->type_item ?> <?= $detail->unit ? $detail->unit->unit_type : 'Type missing' ?>-<?= $detail->unit ? $detail->unit->name : 'Unit missing' ?> <?= number_format($detail->unit_size, 0, '.', ',') ?>m<sup>2</sup>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?= number_format($detail->total, 2, '.', ',') ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?= number_format(floatval($detail->total) * floatval($detail->unit_size), 2, '.', ',') ?>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <?php if (
                                                                            $detail->type_item == 'Unit'
                                                                        ) { ?>
                                                                            <?= number_format(floatval($detail->total) * floatval($detail->unit_size) * floatval($row->month_period), 2, '.', ',') ?>
                                                                        <?php } else { ?>
                                                                            <?= number_format(floatval($detail->total) * floatval($detail->unit_size) * 12, 2, '.', ',') ?>
                                                                        <?php } ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach ?>
                                                        <?php } else { ?>
                                                            <tr>
                                                                <td class="text-capitalize text-center text-muted" colspan="4">
                                                                    no data record
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    <tfooter>
                                                        <tr style="background-color:#666666; color:white;">
                                                            <td class="text-capitalize text-left" colspan="2">total (without tax)</td>
                                                            <td class="text-capitalize text-right" id="sum_month_without_tax">
                                                                <?= number_format($row->without_tax_total_per_month, 2, '.', ',') ?>
                                                            </td>
                                                            <td class="text-capitalize text-right" id="sum_total_without_tax">
                                                                <?= number_format($row->without_tax_total_per_grand, 2, '.', ',') ?>
                                                            </td>
                                                        </tr>
                                                        <tr style="background-color:#666666; color:white;">
                                                            <td class="text-capitalize text-left" colspan="3">total (with tax)</td>
                                                            <td class="text-capitalize text-right" id="sum_total_with_tax">
                                                                <?= number_format($row->without_tax_total_per_grand, 2, '.', ',') ?>
                                                            </td>
                                                        </tr>
                                                    </tfooter>
                                                </table>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col" style="padding-top: 15px; padding-left: 15px; padding-right: 15px; padding-bottom: 2px;">
                                                <div style="background-color: #F9EDFF; border-radius: 2px;">
                                                    <div style="padding-top: 10px; padding-left: 10px; padding-right: 10px; padding-bottom: 10px;">
                                                        <div class="row">
                                                            <div class="col-md-6 col" style="margin-bottom: 2px;">
                                                                <span class="text-muted text-capitalize">created by: <?php echo $row->username; ?>, updated at: <?php echo time_ago($row->updated_at); ?></span>
                                                            </div>

                                                            <div class="col-md-6 col">
                                                                <button type="button" class="btn" style="background-color:black; color:white;"
                                                                    onclick="open_table(<?php echo $seq; ?>)"
                                                                >
                                                                    <i class="fa fa-eye"></i><span style="margin-left: 5px;">See Detail</span>
                                                                </button>

                                                                <button type="button" class="btn btn-warning"
                                                                    onclick="location.href='<?=site_url('Tenancy/Contract/New_Contract/edit/'.$row->id)?>'"
                                                                >
                                                                    <i class="fa fa-edit"></i><span style="margin-left: 5px;">Edit</span>
                                                                </button>

                                                                <a onclick="return confirm('Are you sure?')" 
                                                                    href="<?= site_url('Tenancy/Contract/New_Contract/destroy/'.$row->id); ?>" class="btn btn-danger">
                                                                    <i class="fa fa-trash"></i><span style="margin-left: 5px;">Delete</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#tbl').DataTable();
    });

    function open_header(rows) {
        const elements = document.getElementsByClassName('rows_' + rows);
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].hidden == false) {
                elements[i].hidden = true;
            } else {
                elements[i].hidden = false;
            }
        }
    }

    function open_payment(rows) {
        const elements = document.getElementsByClassName('pay_' + rows);
        for (let i = 0; i < elements.length; i++) {
            if (elements[i].hidden == false) {
                elements[i].hidden = true;
            } else {
                elements[i].hidden = false;
            }
        }
    }

    function open_table(rows) {
        if (document.getElementById('tbl_sum_' + rows).hidden == false) {
            document.getElementById('tbl_sum_' + rows).hidden = true;
        } else {
            document.getElementById('tbl_sum_' + rows).hidden = false;
        }
    }

    function open_document(rows) {
        if (document.getElementById('doc_' + rows).hidden == false) {
            document.getElementById('doc_' + rows).hidden = true;
        } else {
            document.getElementById('doc_' + rows).hidden = false;
        }
    }
</script>



