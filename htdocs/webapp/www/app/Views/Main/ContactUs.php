<?php
use \Shared\Legacy\Error;
use Helpers\Session;

if ($member)
{
    $langs = json_decode($member->profile->languages, true);
    if (!empty($langs)) {
        $prefLang = key($langs);
    }
}
?>

<div class="contact_us">
    <h1><?php echo __('contact_us_title') ?></h1>

    <?php
    echo Error::display($error);

    if(isset($data["success"]))
        echo Error::display($data["success"], "alert alert-success");
    ?>

    <form id="contact_us" action='' method='post'>
        <div class="form-group">
            <label for="name" class="sr-only"><?php echo __('name'); ?></label>
            <input type="text"
                   class="form-control input-lg"
                   id="name"
                   name="name"
                   placeholder="<?php echo __('name'); ?>"
                   value="<?php echo $_POST["name"] ?? ($member
                           ? $member->firstName . " " . $member->lastName . " (" . $member->userName . ")"
                           : "") ?>">
        </div>

        <div class="form-group">
            <label for="email" class="sr-only"><?php echo __('email'); ?></label>
            <input type="text"
                   class="form-control input-lg"
                   id="email"
                   name="email"
                   placeholder="<?php echo __('email'); ?>"
                   value="<?php echo $_POST["email"] ?? (Session::get("email")
                           ? Session::get("email")
                           : "") ?>">
        </div>

        <div class="form-group">
            <label for="lang" class="sr-only"><?php echo __('select_language'); ?>: </label>
            <select id="lang"
                    class="form-control input-lg select-chosen-single"
                    name="lang"
                    data-placeholder="<?php echo __('select_language'); ?>">
                <option></option>
                <?php foreach ($languages as $lang):?>
                    <option <?php echo (isset($_POST["lang"]) && $lang->langID == $_POST["lang"]) || (isset($prefLang) && $prefLang == $lang->langID) ? "selected" : "" ?>>
                        <?php echo "[".$lang->langID."] " . $lang->langName .
                            ($lang->angName != "" && $lang->langName != $lang->angName ? " ( ".$lang->angName." )" : ""); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="message" class="sr-only"><?php echo __('message_content'); ?></label>
            <textarea class="form-control input-lg"
                      id="message"
                      name="message"
                      placeholder="<?php echo __('message_content'); ?>"
                      rows="10"><?php echo $_POST["message"] ?? "" ?></textarea>
        </div>

        <input type="hidden" name="csrfToken" value="<?php echo $data['csrfToken']; ?>" />

        <button type="submit"
                class="g-recaptcha btn btn-primary btn-lg"
                data-sitekey="<?php echo ReCaptcha::getSiteKey(); ?>"
                data-callback='onContactSubmit'
                data-action='submit'><?php echo __('submit'); ?></button>
    </form>
</div>

<style>
    .chosen-choices {
        min-height: 45px;
    }
    .chosen-single {
        min-height: 45px;
    }
    .chosen-container {
        font-size: 16px !important;
    }
    .search-choice {
        line-height: 30px !important;
    }
    .chosen-container-single .chosen-single {
        line-height: 42px !important;
    }
    .chosen-container-multi .chosen-choices li.search-field input[type="text"] {
        height: 42px !important;
    }
    .has-error .chosen-choices, .has-error .chosen-single {
        border-color: #a94442 !important;
    }
</style>

<?php
Assets::js([
    template_url('js/chosen.jquery.min.js?v=2'),
]);

Assets::css([
    template_url('css/chosen.min.css?v=2'),
]);
?>

<script>
    function onContactSubmit(token) {
        document.getElementById("contact_us").submit();
    }

    (function () {
        $("select").chosen().change(function () {
            formGroup = $(this).parents(".form-group");
            formGroup.removeClass('has-error');
            if ($(this).hasClass("select-chosen-single")) {
                $(".chosen-single", formGroup).popover('destroy');
            }
            if ($(this).hasClass("select-chosen-multiple")) {
                $(".chosen-choices", formGroup).popover('destroy');
            }
        });
    })()
</script>
<?php if(Config::get("app.type") == "remote"): ?>
    <script src="https://www.google.com/recaptcha/api.js?hl=<?php echo Language::code()?>" async defer></script>
<?php endif; ?>


