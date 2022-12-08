<?php
use Helpers\Session;

echo Session::message();
?>

<br><br>

<a class="btn btn-link" href="/members">
    <?php echo __('home'); ?>
</a>

<?php if(Session::get("activation_member_id") !== null): ?>
|
<a class="btn btn-link" href="/members/activate/resend/<?php echo Session::get("activation_member_id") ?>">
    <?php echo __('resend_activation_code'); ?>
</a>
<?php endif; ?>