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

                #struk {
                    display: block !important;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 58mm;
                    padding: 0;
                    margin: 0;
                    font-family: Arial, sans-serif;
                    font-size: 12px;
                    /* page-break-before: avoid !important;
                    page-break-after: avoid !important;
                    page-break-inside: avoid !important; */
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
            }
        </style>
    </head>

    <body onload="window.print()" onafterprint="window.close()">
        <div id="struk">
            <?php yield_section('content'); ?>
        </div>
    </body>
</html>
