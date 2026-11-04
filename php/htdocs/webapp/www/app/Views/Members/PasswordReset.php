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

        <?php if(Config::get("app.type") == "remote"): ?>
        <div class="cf-turnstile" data-sitekey="<?php echo ReCaptcha::getSiteKey(); ?>" data-action="reset" data-appearance="<?php echo ReCaptcha::appearance($error ?? null); ?>"></div>
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><?php echo __('continue'); ?></button>
    </form>
</div>

<script>
</script>
<?php if(Config::get("app.type") == "remote"): ?>
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<?php endif; ?>
