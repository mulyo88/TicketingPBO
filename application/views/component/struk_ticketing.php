<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image" href="<?= base_url($this->config->item('PATH_LogoCompany')) ?>">
        <title><?php yield_section('title'); ?></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

        <style>
            @media print {
                @page {
                    size: 58mm auto;
                    margin: 20px;
                }

                body {
                    margin: 0;
                }

                #struk {
                    width: 58mm;
                    margin: 0;
                    padding: 0;
                    font-family: Arial, sans-serif;
                    font-size: 11px;
                }

                table, tbody, tr, th, td {
                    padding: 0 !important;
                    margin: 0 !important;
                    border: 0 !important;
                    border-collapse: collapse !important;
                }

                .line {
                    border-top: 1px dashed #000;
                    margin: 6px 0 !important;
                }

                /* ===== PAGE SPLIT ===== */
                .page-break {
                    page-break-before: always;
                    break-before: page;
                }

                .page-break-after {
                    page-break-after: always;
                    break-after: page;
                }

                .qrcode-wrapper {
                    width: 100%;
                    text-align: center;
                }

                .qrcode-wrapper canvas,
                .qrcode-wrapper img {
                    display: inline-block !important;
                    margin: 0 auto !important;
                }
            }
        </style>
    </head>

    <body onload="window.print()" onafterprint="window.close()">
        <div id="struk">
            <?php yield_section('content'); ?>
        </div>
    </body>
</html>
