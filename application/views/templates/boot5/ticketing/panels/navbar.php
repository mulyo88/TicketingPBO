<nav class="shadow-sm p-3" style="background-color:
    <?php if ($this->uri->segment(4) == 'Checkin_Scan'): ?>
        #E8FF00
    <?php elseif ($this->uri->segment(4) == 'Checkout'): ?>
        #940000
    <?php elseif ($this->uri->segment(4) == 'Checkin'): ?>
        #1D0096
    <?php endif; ?>
;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <div class="text-capitalize fw-bold"
                style="color:
                    <?php if ($this->uri->segment(4) == 'Checkin_Scan'): ?>
                        #000000
                    <?php elseif ($this->uri->segment(4) == 'Checkout'): ?>
                        #FFFFFF
                    <?php elseif ($this->uri->segment(4) == 'Checkin'): ?>
                        #FFFFFF
                    <?php endif; ?>
                ;"
            >
                <?= isset($title) ? $title : 'Dashboard'; ?>
                <span>-</span>
                <span><?php echo $building->code; ?> - <?php echo $venue->code; ?> - <?php echo isset($counter) ? $counter->code : (isset($gate) ? $gate->code : '-'); ?></span>
            </div>

            <div class="d-flex flex-row text-light">
                <a onclick="reload_data()" href="#" class="nav-link me-2"
                    style="color:
                        <?php if ($this->uri->segment(4) == 'Checkin_Scan'): ?>
                            #000000
                        <?php elseif ($this->uri->segment(4) == 'Checkout'): ?>
                            #FFFFFF
                        <?php elseif ($this->uri->segment(4) == 'Checkin'): ?>
                            #FFFFFF
                        <?php endif; ?>
                    ;"
                >
                    <i class="fa-solid fa-refresh"></i>
                </a>
                
                <?php if ($this->uri->segment(4) == 'Checkin_Scan'): ?>
                    <a
                        href="<?=site_url('Tenancy/Ticketing/Trans/Checkin_Scan/index')?>"
                        class="nav-link"
                    >
                        <i class="fa-solid fa-home" style="color: #000000;"></i>
                    </a>
                <?php elseif ($this->uri->segment(4) == 'Checkout'): ?>
                    <a
                        href="<?=site_url('Tenancy/Ticketing/Trans/Checkout/index')?>"
                        class="nav-link"
                    >
                        <i class="fa-solid fa-home" style="color: #FFFFFF;"></i>
                    </a>
                <?php elseif ($this->uri->segment(4) == 'Checkin'): ?>
                    <a
                        href="<?=site_url('Tenancy/Ticketing/Trans/Checkin/index')?>"
                        class="nav-link"
                    >
                        <i class="fa-solid fa-home" style="color: #FFFFFF;"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>