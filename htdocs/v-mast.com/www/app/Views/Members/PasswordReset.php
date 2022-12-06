<?php
use Shared\Legacy\Error;
?>

<div class="members_login">
    <h1><?php echo __('passwordreset_title'); ?></h1>

    <?php
    echo Error::display($error);
    ?>

    <form action='' method='post' style="width: 500px" id="reset_password">
        <div class="form-group">
            <label for="email"><?php echo __('enter_email') ?></label>
            <input type="text" class="form-control" id="email" name="email"
                   placeholder="<?php echo __('enter_email') ?>" value="">
        </div>

        <input type="hidden" name="csrfToken" value="<?php echo $data['csrfToken']; ?>"/>

        <button type="submit"
                class="g-recaptcha btn btn-primary"
                data-sitekey="<?php echo ReCaptcha::getSiteKey(); ?>"
                data-callback='onResetSubmit'
                data-action='submit'><?php echo __('continue'); ?></button>
    </form>
</div>

<script>
    function onResetSubmit(token) {
        document.getElementById("reset_password").submit();
    }
</script>
<?php if(Config::get("app.type") == "remote"): ?>
<script src="https://www.google.com/recaptcha/api.js?hl=<?php echo Language::code()?>" async defer></script>
<?php endif; ?>
