<?php
/**
 * Created by PhpStorm.
 * User: mXaln
 * Date: 15.11.2016
 * Time: 13:24
 */
use Shared\Legacy\Error;

echo Error::display($error);

?>
<div class="redirect_msg" style="font-size: 18px; color: #ff001e;"></div>

<script>
    (function () {
        var i = 5;
        setInterval(function () {
            $(".redirect_msg").text("Redirecting to profile in " + i + " seconds");
            i--;
        }, 1000);

        setTimeout(function () {
            window.location = "/members/profile"
        }, 5000);
    })()
</script>
