<nav class="shadow-sm p-3" style="background-color: #8800AD;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <span class="text-capitalize fw-bold text-light">
                <?= isset($title) ? $title : 'Dashboard'; ?>
                <span>-</span>
                <span><?php echo $building->code; ?></span>
            </span>

            <div class="d-flex flex-row text-light">
                <a onclick="reload_data()" href="#" class="nav-link me-2"><i class="fa-solid fa-refresh"></i></a>
                
                <a
                    href="<?=site_url('Tenancy/Inventory/Selling/POS/index')?>"
                    class="nav-link"
                >
                    <i class="fa-solid fa-home"></i>
                </a>
            </div>
        </div>
    </div>
</nav>