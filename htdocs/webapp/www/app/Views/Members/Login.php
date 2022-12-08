<?php
use Helpers\Session;
use Shared\Legacy\Error;
?>
<div class="members_login center-block"><br />
	<h1><?php echo __('login'); ?></h1>
	
	<?php
	echo Error::display($error);
    echo Session::message();
	?>

	<form action='' method='post' style="width: 500px" id="log_in">
		<div class="form-group">
			<label for="email" class="sr-only">Email / <?php echo __('userNameOrEmail'); ?></label>
			<input type="text" class="form-control input-lg" id="email" name="email" placeholder="<?php echo __('userNameOrEmail'); ?>" required="" value="<?php echo isset($_POST["email"]) ? $_POST["email"] : ""?>">
		</div>

		<div class="form-group password_group" <?php echo Config::get("app.type") == "local" ? 'style="display: none"' : "" ?>>
			<label for="password" class="sr-only"><?php echo __('password'); ?></label>
			<input type="password" class="form-control input-lg"
                   id="password" name="password" required=""
                   placeholder="<?php echo __('password'); ?>"
                   value="<?php echo Config::get("app.type") == "local" ? "default" : "" ?>">
		</div>

        <?php if(Config::get("app.type") == "local"): ?>
            <div class="form-group">
                <input type="checkbox" id="isSuperAdmin">
                <label for="isSuperAdmin">Admin</label>
            </div>
        <?php endif; ?>

		<input type="hidden" name="csrfToken" value="<?php echo $data['csrfToken']; ?>" />
    <div class="row">
      <div class="col-sm-4" style="border-right:1px solid #ccc">
          <?php if(Config::get("app.type") == "remote" && Session::get('loginTry')>=3):?>
              <button type="submit" class="g-recaptcha btn btn-primary btn-lg"
                      style="width: 8em;"
                      data-sitekey="<?php echo ReCaptcha::getSiteKey(); ?>"
                      data-callback='onLoginSubmit'
                      data-action='submit'><?php echo __('login'); ?></button>
          <?php else: ?>
              <button type="submit" class="btn btn-primary btn-lg" style="width: 8em;"><?php echo __('login'); ?></button>
          <?php endif;?>
      </div>
      <div class="col-sm-8">
	      <a href="<?php echo SITEURL?>members/passwordreset" class=""><?php echo __('forgot_password'); ?></a><br />
	      <?php echo __('dont_have_account'); ?> <a href='<?php echo SITEURL;?>members/signup'><?php echo __('signup'); ?></a>
      </div>
    </div>
	</form>
</div>

<script>
    function onLoginSubmit(token) {
        document.getElementById("log_in").submit();
    }
</script>

<?php if(Config::get("app.type") == "remote"): ?>
<script src="https://www.google.com/recaptcha/api.js?hl=<?php echo Language::code()?>" async defer></script>
<?php endif; ?>
