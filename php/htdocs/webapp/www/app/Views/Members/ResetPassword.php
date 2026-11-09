<?php 
use Shared\Legacy\Error;
?>

<div class="members_login">
    <?php echo Error::display($error); ?>

    <h1><?php echo __('passwordreset_title'); ?></h1>
    <h3><?php echo __("enter_new_password") ?></h3>

    <?php
    if(isset($data['success'])){
        echo "<div class='alert alert-success'>";
        echo $data['success'];
        echo "</div>";
    }
    else {
        if ($data['step'] == 2) {
            ?>

            <form action='' method='post' style="width: 500px">
                <div class="form-group">
                    <label for="password"><?php echo __('password'); ?></label>
                    <input type="password" class="form-control" id="password" name="password"
                           placeholder="<?php echo __('password'); ?>" value="">
                </div>

                <div class="form-group">
                    <label for="passwordConfirm"><?php echo __('confirm_password'); ?></label>
                    <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm"
                           placeholder="<?php echo __('confirm_password'); ?>" value="">
                </div>

                <input type="hidden" name="csrfToken" value="<?php echo $data['csrfToken']; ?>"/>

                <button type="submit" name="submit"
                        class="btn btn-primary"><?php echo __('continue'); ?></button>
            </form>

            <?php
        }
    }
    ?>
</div>


