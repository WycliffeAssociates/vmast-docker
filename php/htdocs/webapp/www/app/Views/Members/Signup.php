<?php

use Helpers\Constants\Projects;
use Shared\Legacy\Error;
?>

<div class="members_login center-block" style="width:370px;">
    <h1><?php echo __('login_message'); ?></h1>
    <p><?php echo __('already_member'); ?> <a href='<?php echo SITEURL;?>members/login'><?php echo __('login'); ?></a>
    <?php if (isset($error) AND is_array($error)):?>
    <script>
    $(function(){
    <?php foreach($error as $k=>$v):?>
    <?php if (in_array($k, array('tou', 'sof'))):?>
      $("input[name=<?=$k?>]").parents("label").popover({
              trigger: 'manual',
              placement: 'right',
              container: 'body',
              delay: 0,
              content:  "<?=$v?>"
            }).popover('show');
    <?php elseif($k == 'recaptcha' && Config::get("app.type") == "remote"):?>
      $(".g-recaptcha").popover({
              trigger: 'manual',
              placement: 'right',
              container: 'body',
              delay: 0,
              content:  "<?=$v?>"
            }).popover('show');
    <?php elseif(in_array($k, array('projects', 'proj_lang'))):?>
        var formGroup = $("select#<?=$k?>").parents(".form-group");
        formGroup.addClass('has-error');
        var popover = $(".chosen-single", formGroup);
        if ($("select#<?=$k?>").hasClass("select-chosen-multiple")) {
            popover = $(".chosen-choices", formGroup);
        }
        popover.popover({
            trigger: 'manual',
            placement: 'right',
            container: 'body',
            delay: 0,
            content:  "<?=$v?>"
        }).popover('show');
    <?php else:?>
      $("input[name=<?=$k?>]").popover({
              trigger: 'manual',
              placement: 'right',
              container: 'body',
              delay: 0,
              content:  "<?=$v?>"
            }).popover('show');
    <?php endif;?>
    <?php endforeach;?>
    });
    </script>
    <?php endif;?>
    <form action='' method='post' id="sign_up">
        <div class="form-group">
            <label for="userName" class="sr-only"><?php echo __('userName'); ?></label>
            <input type="text" data-type="login" data-custom-error="<?=__('userName_characters_error')?>" data-empty-error="<?=__('userName_length_error')?>" class="form-control input-lg" id="userName" name="userName" placeholder="<?php echo __('userName'); ?>" value="<?php if(!empty($error)){ echo $_POST['userName']; } ?>">
        </div>

        <div class="form-group">
            <label for="firstName" class="sr-only"><?php echo __('firstName'); ?></label>
            <input type="text" data-type="name" data-custom-error="<?=__('firstName_length_error')?>" data-empty-error="<?=__('firstName_length_error')?>" class="form-control input-lg" id="firstName" name="firstName" placeholder="<?php echo __('firstName'); ?>" value="<?php if(!empty($error)){ echo $_POST['firstName']; } ?>">
        </div>

        <div class="form-group">
            <label for="lastName" class="sr-only"><?php echo __('lastName'); ?></label>
            <input type="text" data-type="name" data-custom-error="<?=__('lastName_length_error')?>" data-empty-error="<?=__('lastName_length_error')?>" class="form-control input-lg" id="lastName" name="lastName" placeholder="<?php echo __('lastName'); ?>" value="<?php if(!empty($error)){ echo $_POST['lastName']; } ?>">
        </div>

        <div class="form-group">
            <label for="email" class="sr-only">Email</label>
            <input type="text" data-type="email" data-custom-error="<?=__('enter_valid_email_error')?>" data-empty-error="<?=__('enter_valid_email_error')?>" class="form-control input-lg" id="email" name="email" placeholder="Email" value="<?php if(!empty($error)){ echo $_POST['email']; } ?>">
        </div>

        <div class="form-group">
            <label for="password" class="sr-only"><?php echo __('password'); ?></label>
            <input type="password" data-type="password" data-custom-error="<?=__('password_short_error')?>" data-empty-error="<?=__('password_short_error')?>" class="form-control input-lg" id="password" name="password" placeholder="<?php echo __('password'); ?>" value="">
        </div>

        <div class="form-group">
            <label for="passwordConfirm" class="sr-only"><?php echo __('confirm_password'); ?></label>
            <input type="password" data-type="confirm" data-custom-error="<?=__('passwords_notmatch_error')?>" data-empty-error="<?=__('passwords_notmatch_error')?>" class="form-control input-lg" id="passwordConfirm" name="passwordConfirm" placeholder="<?php echo __('confirm_password'); ?>" value="">
        </div>

        <div class="form-group">
            <label for="projects" class="sr-only"><?php echo __('select_project'); ?>: </label>
            <select id="projects"
                    class="form-control input-lg select-chosen-single"
                    name="projects"
                    data-type="projects"
                    data-empty-error="<?php echo __('projects_empty_error')?>"
                    data-placeholder="<?php echo __("select_project") ?>">
                <option></option>
                <?php foreach (Projects::list() as $project): ?>
                <option <?php echo isset($_POST["projects"]) && $_POST["projects"] == $project ? "selected" : "" ?>
                        value="<?php echo $project ?>"><?php echo __($project) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="proj_lang" class="sr-only"><?php echo __('proj_lang_select'); ?>: </label>
            <select id="proj_lang"
                    class="form-control input-lg select-chosen-single"
                    name="proj_lang"
                    data-type="proj_lang"
                    data-empty-error="<?php echo __('proj_lang_empty_error')?>"
                    data-placeholder="<?php echo __('proj_lang_select'); ?>">
                <option></option>
                <?php foreach ($languages as $lang):?>
                    <?php if($lang->langID == "en") continue; ?>
                    <option <?php echo isset($_POST["proj_lang"]) && $lang->langID == $_POST["proj_lang"] ? "selected" : "" ?>
                            value="<?php echo $lang->langID; ?>">
                        <?php echo "[".$lang->langID."] " . $lang->langName .
                            ($lang->angName != "" && $lang->langName != $lang->angName ? " ( ".$lang->angName." )" : ""); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label><input name="tou" data-type="checkbox" data-custom-error="<?=__('tou_accept_error')?>" id="tou" type="checkbox" value="1" /> <?php echo __('tou'); ?></label><br><br>
            <label><input name="sof" data-type="checkbox" data-custom-error="<?=__('sof_accept_error')?>" id="sof" type="checkbox" value="1" /> <?php echo __('sof'); ?></label>
        </div>

        <input type="hidden" name="csrfToken" value="<?php echo $data['csrfToken']; ?>" />

        <button type="submit"
                class="g-recaptcha btn btn-primary btn-lg"
                data-sitekey="<?php echo ReCaptcha::getSiteKey(); ?>"
                data-callback='onSignupSubmit'
                data-action='submit'><?php echo __('signup'); ?></button>
    </form>
</div>

<!-- Modal -->
<div class="modal fade" id="sof_modal" tabindex="-1" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title">Statement of Faith</h1>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <p>
                            <strong>Essential Beliefs</strong><br>
                            We consider Essential beliefs to be those that define us as believers in Jesus Christ. These cannot be disregarded or compromised.
                        </p>

                        <p>We believe:</p>

                        <ul>
                            <li>The Bible is divinely inspired by God and has final authority.</li>
                            <li>God is one and exists in three persons: God the Father, God the Son, and God the Holy Spirit.</li>
                            <li>Because of the fall of man, all humans are sinful, and in need of salvation.</li>
                            <li>The death of Christ is a substitute for sinners and provides for the cleansing of those who believe.</li>
                            <li>By God’s grace, through faith, man receives salvation as a free gift because of Jesus’ death and resurrection.</li>
                            <li>The resurrection of all—the saved to eternal life and the lost to eternal punishment.</li>
                        </ul>
                        <br />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-success" id="sof_agree" value="<?php echo __('accept_btn'); ?>" />
                <input type="button" class="btn btn-danger" id="sof_cancel" value="<?php echo __('deny_btn'); ?>" />
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="tou_modal" tabindex="-1" role="dialog" style="z-index: 9999;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h1 class="modal-title">Freedom</h1>
            </div>
            <div class="modal-body">
                <div class="fl-rich-text">
                    <p>Except where otherwise noted, content on BibleInEveryLanguage.org is licensed under a&nbsp;<a href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Attribution-ShareAlike 4.0 International License</a>.</p>
                    <h4>Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)</h4>
                    <p>This is a human-readable summary of (and not a substitute for) the&nbsp;license.</p>
                    <h4>You are free to:</h4>
                    <ul>
                        <li><strong>Share</strong>&nbsp;— copy and redistribute the material in any medium or format</li>
                        <li><strong>Adapt</strong>&nbsp;— remix, transform, and build upon the material<br> for any purpose, even commercially.</li>
                    </ul>
                    <p>The licensor cannot revoke these freedoms as long as you follow the license terms.</p>
                    <h4>Under the following conditions:</h4>
                    <ul>
                        <li><strong>Attribution&nbsp;</strong>— You must attribute the work as follows: “Original work available at https://BibleInEveryLanguage.org.” Attribution statements in derivative works should not in any way suggest that we endorse you or your use of this work.</li>
                        <li><strong>ShareAlike&nbsp;</strong>— If you remix, transform, or build upon the material, you must distribute your contributions under the same license as the original.</li>
                        <li><strong>No additional restrictions</strong>&nbsp;— You may not apply legal terms or technological measures that legally restrict others from doing anything the license permits.</li>
                    </ul>
                    <h4>Notices:</h4>
                    <p>You do not have to comply with the license for elements of the material in the public domain or where your use is permitted by an applicable exception or limitation.</p>
                    <p>No warranties are given. The license may not give you all of the permissions necessary for your intended use. For example, other rights such as publicity, privacy, or moral rights may limit how you use the material.</p>
                    <h4>Attribution of BibleInEveryLanguage.org and Door43 Contributors</h4>
                    <p>When importing a resource (e.g. a book, Bible study, etc.) into BibleInEveryLanguage.org, the original work must be attributed as specified by the open license under which it is available. For example, the artwork used in Open Bible Stories is available under an open license and is clearly attributed on the project’s&nbsp;main page.</p>
                    <p>Contributors to projects on BibleInEveryLanguage.org agree that&nbsp;the attribution that occurs automatically in the revision history of every page is sufficient attribution for their work.&nbsp;That is, every contributor to a translation on BibleInEveryLanguage.org into another language may be listed as “the BibleInEveryLanguage.org and&nbsp;Door43 World Missions Community” or something to that effect. The individual contributions of each individual contributor are preserved in the revision history for that translation.</p>
                </div>
            </div>
            <div class="modal-footer">
                <input type="button" class="btn btn-success" id="tou_agree" value="<?php echo __('accept_btn'); ?>" />
                <input type="button" class="btn btn-danger" id="tou_cancel" value="<?php echo __('deny_btn'); ?>" />
            </div>
        </div>
    </div>
</div>
<style>
    .popover {
      z-index:5;
    }
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
    template_url('js/formvalidation.js?v=2'),
    template_url('js/chosen.jquery.min.js?v=2'),
]);

Assets::css([
    template_url('css/chosen.min.css?v=2'),
]);
?>

<script>
    function onSignupSubmit(token) {
        document.getElementById("sign_up").submit();
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
