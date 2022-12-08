<div class="maintenance">
    <div class="maintenance_img">
        <img class="mt_gear" src="<?php echo template_url("img/gear.png") ?>">
        <img class="mt_wrench" src="<?php echo template_url("img/wrench.png") ?>" width="150">
    </div>
    <div class="maintenance_title"><?php echo __("maintenance_work") ?></div>
</div>

<script>
    setInterval(checkMaintenance, 60000);

    function checkMaintenance() {
        $.get("/rpc/check_maintenance", function(data) {
            if (!data.isMaintenance) {
                window.location = "/";
            }
        });
    }
</script>
