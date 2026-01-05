<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image" href="<?= base_url($this->config->item('PATH_LogoCompany')) ?>">
    <title><?php yield_section('title'); ?></title>

    <?php include_view('templates/boot5/sections/style'); ?>
    <?php yield_section('page-style'); ?>
  </head>
  <body style="background-color: #F2BFFF;">
    <?php include_view('templates/boot5/pos/panels/navbar'); ?>
    
    <div class="container-fluid">
        <?php yield_section('content'); ?>
    </div>

    <?php include_view('templates/boot5/sections/script'); ?>
    <?php yield_section('page-script'); ?>
  </body>
</html>
