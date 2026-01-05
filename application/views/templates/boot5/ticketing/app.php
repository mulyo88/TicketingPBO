<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image" href="<?= base_url($this->config->item('PATH_LogoCompany')) ?>">
        <title><?php yield_section('title'); ?></title>

        <?php include_view('templates/boot5/sections/style'); ?>
        <?php yield_section('page-style'); ?>

        <style>
          body {
                position: relative;
                background: url('<?php
                    if ($this->uri->segment(4) == "Checkin_Scan") {
                        echo "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1173&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D";
                    } elseif ($this->uri->segment(4) == "Checkout") {
                        echo base_url("assets/img/sunset_mall.jpeg");
                    } elseif ($this->uri->segment(4) == "Checkin") {
                        echo "https://marketplace.canva.com/EAGQ4hBhXII/1/0/1600w/canva-blue-abstract-desktop-wallpaper-htGu4av79Lo.jpg";
                    } else {
                        echo "https://images.unsplash.com/photo-1507525428034-b723cf961d3e";
                    }
                ?>') center/cover no-repeat;
          }

          body::before {
              content: "";
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background-color: #E5DEBE;
              opacity: 0.5;
              z-index: -1;
          }
        </style>
    </head>
    <body>
        <?php include_view('templates/boot5/ticketing/panels/navbar'); ?>
        
        <div class="container-fluid">
            <?php yield_section('content'); ?>
        </div>

        <?php include_view('templates/boot5/sections/script'); ?>
        <?php yield_section('page-script'); ?>
    </body>
</html>
