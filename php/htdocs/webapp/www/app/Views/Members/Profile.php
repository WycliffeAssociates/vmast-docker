<?php
use Helpers\Session;
use Shared\Legacy\Error;
?>

<h1><?php echo __('profile_message'); ?></h1>

<form action='' method='post' style="width: 900px" class="form-horizontal profile_form">

    <div class="profile_messages">
        <?php
        echo Error::display($error);
        echo Session::message();
        ?>
    </div>

    <h3><?php echo __('personal'); ?></h3>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["userName"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('userName'); ?>:</span>
                <?php if (isset($data["errors"]["userName"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["userName"] ?>'></span>
                <?php endif; ?>
            </div>
            <input type="text" class="form-control" name="userName"
                   value="<?php echo $_POST["userName"] ?? $member->userName ?>">
        </label>
    </div>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["firstName"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('firstName'); ?>:</span>
                <?php if (isset($data["errors"]["firstName"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["firstName"] ?>'></span>
                <?php endif; ?>
            </div>
            <input type="text" class="form-control" name="firstName"
                   value="<?php echo $_POST["firstName"] ?? $member->firstName ?>">
        </label>&nbsp;&nbsp;&nbsp;&nbsp;
        <label class="<?php echo isset($data["errors"]["lastName"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('lastName'); ?>:</span>
                <?php if (isset($data["errors"]["lastName"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["lastName"] ?>'></span>
                <?php endif; ?>
            </div>
            <input type="text" class="form-control" name="lastName"
                   value="<?php echo $_POST["lastName"] ?? $member->lastName ?>">
        </label>
    </div>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["email"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('email'); ?>:</span>
                <?php if (isset($data["errors"]["email"])):?>
                <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["email"] ?>'></span>
                <?php endif; ?>
            </div>
            <input type="text" class="form-control" name="email"
                   value="<?php echo $_POST["email"] ?? $member->email ?>">
        </label>
    </div>

    <h3><?php echo __('common'); ?></h3>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["prefered_roles"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('prefered_roles'); ?>:</span>
                <?php if (isset($data["errors"]["prefered_roles"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["prefered_roles"] ?>'></span>
                <?php endif; ?>
            </div>
        </label>
        <div class="form-control">
            <label><input type="checkbox" class="tr_role" name="prefered_roles[]" value="translator"
                    <?php echo isset($_POST["prefered_roles"]) && in_array("translator", $_POST["prefered_roles"]) ? "checked" :
                        (strpos($profile->prefered_roles, "translator") !== false ? "checked" : "") ?>> <?php echo __('translator'); ?> &nbsp;</label>
            <label><input type="checkbox" class="fc_role" name="prefered_roles[]" value="facilitator"
                    <?php echo isset($_POST["prefered_roles"]) && in_array("facilitator", $_POST["prefered_roles"]) ? "checked" :
                        (strpos($profile->prefered_roles, "facilitator") !== false ? "checked" : "") ?>> <?php echo __('facilitator'); ?> &nbsp;</label>
            <label><input type="checkbox" class="ch_role" name="prefered_roles[]" value="checker"
                    <?php echo isset($_POST["prefered_roles"]) && in_array("checker", $_POST["prefered_roles"]) ? "checked" :
                        (strpos($profile->prefered_roles, "checker") !== false ? "checked" : "") ?>> <?php echo __('checker'); ?> &nbsp;</label>
        </div>
    </div>

    <label for="known_languages" class="<?php echo isset($data["errors"]["langs"]) ? "label_error" : "" ?>">
        <div class="profile_error">
            <span><?php echo __('known_languages'); ?>:</span>
            <?php if (isset($data["errors"]["langs"])):?>
                <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["langs"] ?>'></span>
            <?php endif; ?>
        </div>
    </label>
    <div class="form-group">
        <div class="language_add glyphicon glyphicon-plus"></div>
        <div class="language_add_input">
            <select class="form-control langs" name="langs[]" multiple data-placeholder="<?php echo __("show_langs_window")?>" disabled >
                <?php if(isset($_POST['langs'])): ?>
                    <?php foreach ($_POST['langs'] as $lang): ?>
                        <option value="<?php echo $lang?>" selected><?php echo $lang?></option>
                    <?php endforeach; ?>
                <?php else:?>
                    <?php foreach (($profile->languages ? json_decode($profile->languages, true) : []) as $lang => $values): ?>
                        <option value="<?php echo $lang.":".$values[0].":".$values[1]?>" selected><?php echo $lang?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="clear"></div>
    </div>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["bbl_trans_yrs"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('bbl_trans_yrs'); ?>:</span>
                <?php if (isset($data["errors"]["bbl_trans_yrs"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["bbl_trans_yrs"] ?>'></span>
                <?php endif; ?>
            </div>
        </label>
        <div class="form-control">
            <label><input type="radio" name="bbl_trans_yrs" value="1"
                    <?php echo isset($_POST["bbl_trans_yrs"]) && $_POST["bbl_trans_yrs"] == 1 ? "checked" :
                        ($profile->bbl_trans_yrs == 1 ? "checked" : "") ?>> 0 &nbsp;</label>
            <label><input type="radio" name="bbl_trans_yrs" value="2"
                    <?php echo isset($_POST["bbl_trans_yrs"]) && $_POST["bbl_trans_yrs"] == 2 ? "checked" :
                        ($profile->bbl_trans_yrs == 2 ? "checked" : "") ?>> 1 &nbsp;</label>
            <label><input type="radio" name="bbl_trans_yrs" value="3"
                    <?php echo isset($_POST["bbl_trans_yrs"]) && $_POST["bbl_trans_yrs"] == 3 ? "checked" :
                        ($profile->bbl_trans_yrs == 3 ? "checked" : "") ?>> 2 &nbsp;</label>
            <label><input type="radio" name="bbl_trans_yrs" value="4"
                    <?php echo isset($_POST["bbl_trans_yrs"]) && $_POST["bbl_trans_yrs"] == 4 ? "checked" :
                        ($profile->bbl_trans_yrs == 4 ? "checked" : "") ?>> 3+ &nbsp;</label>
        </div>
    </div>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["othr_trans_yrs"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('othr_trans_yrs'); ?>:</span>
                <?php if (isset($data["errors"]["othr_trans_yrs"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["othr_trans_yrs"] ?>'></span>
                <?php endif; ?>
            </div>
        </label>
        <div class="form-control">
            <label><input type="radio" name="othr_trans_yrs" value="1"
                    <?php echo isset($_POST["othr_trans_yrs"]) && $_POST["othr_trans_yrs"] == 1 ? "checked" :
                        ($profile->othr_trans_yrs == 1 ? "checked" : "") ?>> 0 &nbsp;</label>
            <label><input type="radio" name="othr_trans_yrs" value="2"
                    <?php echo isset($_POST["othr_trans_yrs"]) && $_POST["othr_trans_yrs"] == 2 ? "checked" :
                        ($profile->othr_trans_yrs == 2 ? "checked" : "") ?>> 1 &nbsp;</label>
            <label><input type="radio" name="othr_trans_yrs" value="3"
                    <?php echo isset($_POST["othr_trans_yrs"]) && $_POST["othr_trans_yrs"] == 3 ? "checked" :
                        ($profile->othr_trans_yrs == 3 ? "checked" : "") ?>> 2 &nbsp;</label>
            <label><input type="radio" name="othr_trans_yrs" value="4"
                    <?php echo isset($_POST["othr_trans_yrs"]) && $_POST["othr_trans_yrs"] == 4 ? "checked" :
                        ($profile->othr_trans_yrs == 4 ? "checked" : "") ?>> 3+ &nbsp;</label>
        </div>
    </div>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["bbl_knwlg_degr"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('bbl_knwlg_degr'); ?>:</span>
                <?php if (isset($data["errors"]["bbl_knwlg_degr"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["bbl_knwlg_degr"] ?>'></span>
                <?php endif; ?>
            </div>
        </label>
        <div class="form-control">
            <label><input type="radio" name="bbl_knwlg_degr" value="1"
                    <?php echo isset($_POST["bbl_knwlg_degr"]) && $_POST["bbl_knwlg_degr"] == 1 ? "checked" :
                        ($profile->bbl_knwlg_degr == 1 ? "checked" : "") ?>> <?php echo __('weak'); ?> &nbsp;</label>
            <label><input type="radio" name="bbl_knwlg_degr" value="2"
                    <?php echo isset($_POST["bbl_knwlg_degr"]) && $_POST["bbl_knwlg_degr"] == 2 ? "checked" :
                        ($profile->bbl_knwlg_degr == 2 ? "checked" : "") ?>> <?php echo __('moderate'); ?> &nbsp;</label>
            <label><input type="radio" name="bbl_knwlg_degr" value="3"
                    <?php echo isset($_POST["bbl_knwlg_degr"]) && $_POST["bbl_knwlg_degr"] == 3 ? "checked" :
                        ($profile->bbl_knwlg_degr == 3 ? "checked" : "") ?>> <?php echo __('strong'); ?> &nbsp;</label>
            <label><input type="radio" name="bbl_knwlg_degr" value="4"
                    <?php echo isset($_POST["bbl_knwlg_degr"]) && $_POST["bbl_knwlg_degr"] == 4 ? "checked" :
                        ($profile->bbl_knwlg_degr == 4 ? "checked" : "") ?>> <?php echo __('expert'); ?> &nbsp;</label>
        </div>
    </div>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["mast_evnts"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('mast_evnts'); ?>:</span>
                <?php if (isset($data["errors"]["mast_evnts"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["mast_evnts"] ?>'></span>
                <?php endif; ?>
            </div>
        </label>
        <div class="form-control">
            <label><input type="radio" name="mast_evnts" value="1"
                    <?php echo isset($_POST["mast_evnts"]) && $_POST["mast_evnts"] == 1 ? "checked" :
                        ($profile->mast_evnts == 1 ? "checked" : "") ?>> 0 &nbsp;</label>
            <label><input type="radio" name="mast_evnts" value="2"
                    <?php echo isset($_POST["mast_evnts"]) && $_POST["mast_evnts"] == 2 ? "checked" :
                        ($profile->mast_evnts == 2 ? "checked" : "") ?>> 1 &nbsp;</label>
            <label><input type="radio" name="mast_evnts" value="3"
                    <?php echo isset($_POST["mast_evnts"]) && $_POST["mast_evnts"] == 3 ? "checked" :
                        ($profile->mast_evnts == 3 ? "checked" : "") ?>> 2 &nbsp;</label>
            <label><input type="radio" name="mast_evnts" value="4"
                    <?php echo isset($_POST["mast_evnts"]) && $_POST["mast_evnts"] == 4 ? "checked" :
                        ($profile->mast_evnts == 4 ? "checked" : "") ?>> 3+ &nbsp;</label>
        </div>
    </div>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["mast_role"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('mast_role'); ?>:</span>
                <?php if (isset($data["errors"]["mast_role"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["mast_role"] ?>'></span>
                <?php endif; ?>
            </div>
        </label>
        <div class="form-control">
            <label><input type="checkbox" name="mast_role[]" value="translator" disabled
                    <?php echo isset($_POST["mast_role"]) && in_array("translator", $_POST["mast_role"]) ? "checked" :
                        (strpos($profile->mast_role, "translator") !== false ? "checked" : "") ?>> <?php echo __('translator'); ?> &nbsp;</label>
            <label><input type="checkbox" name="mast_role[]" value="facilitator" disabled
                    <?php echo isset($_POST["mast_role"]) && in_array("facilitator", $_POST["mast_role"]) ? "checked" :
                        (strpos($profile->mast_role, "facilitator") !== false ? "checked" : "") ?>> <?php echo __('facilitator'); ?> &nbsp;</label>
            <label><input type="checkbox" name="mast_role[]" value="l2_checker" disabled
                    <?php echo isset($_POST["mast_role"]) && in_array("l2_checker", $_POST["mast_role"]) ? "checked" :
                        (strpos($profile->mast_role, "l2_checker") !== false ? "checked" : "") ?>> <?php echo __('l2_checker'); ?> &nbsp;</label>
            <label><input type="checkbox" name="mast_role[]" value="l3_checker" disabled
                    <?php echo isset($_POST["mast_role"]) && in_array("l3_checker", $_POST["mast_role"]) ? "checked" :
                        (strpos($profile->mast_role, "l3_checker") !== false ? "checked" : "") ?>> <?php echo __('l3_checker'); ?> &nbsp;</label>
        </div>
    </div>

    <div class="form-group">
        <label class="<?php echo isset($data["errors"]["teamwork"]) ? "label_error" : "" ?>">
            <div class="profile_error">
                <span><?php echo __('teamwork'); ?>:</span>
                <?php if (isset($data["errors"]["teamwork"])):?>
                    <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["teamwork"] ?>'></span>
                <?php endif; ?>
            </div>
        </label>
        <div class="form-control">
            <label><input type="radio" name="teamwork" value="1"
                    <?php echo isset($_POST["teamwork"]) && $_POST["teamwork"] == 1 ? "checked" :
                        ($profile->teamwork == 1 ? "checked" : "") ?>> <?php echo __('rarely'); ?> &nbsp;</label>
            <label><input type="radio" name="teamwork" value="2"
                    <?php echo isset($_POST["teamwork"]) && $_POST["teamwork"] == 2 ? "checked" :
                        ($profile->teamwork == 2 ? "checked" : "") ?>> <?php echo __('some'); ?> &nbsp;</label>
            <label><input type="radio" name="teamwork" value="3"
                    <?php echo isset($_POST["teamwork"]) && $_POST["teamwork"] == 3 ? "checked" :
                        ($profile->teamwork == 3 ? "checked" : "") ?>> <?php echo __('much'); ?> &nbsp;</label>
            <label><input type="radio" name="teamwork" value="4"
                    <?php echo isset($_POST["teamwork"]) && $_POST["teamwork"] == 4 ? "checked" :
                        ($profile->teamwork == 4 ? "checked" : "") ?>> <?php echo __('frequently'); ?> &nbsp;</label>
        </div>
    </div>

    <div class="facilitator_section
            <?php echo isset($_POST["prefered_roles"]) && in_array("facilitator", $_POST["prefered_roles"]) ? "shown" :
                (strpos($profile->prefered_roles, "facilitator") !== false ? "shown" : "") ?>">
        <h3><?php echo __('facilitator_skills'); ?></h3>

        <div class="form-group">
            <label class="<?php echo isset($data["errors"]["mast_facilitator"]) ? "label_error" : "" ?>">
                <div class="profile_error">
                    <span><?php echo __('mast_facilitator'); ?>:</span>
                    <?php if (isset($data["errors"]["mast_facilitator"])):?>
                        <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["mast_facilitator"] ?>'></span>
                    <?php endif; ?>
                </div>
            </label>
            <div class="form-control">
                <label>
                    <input type="radio" name="mast_facilitator" value="1"
                        <?php echo isset($_POST["mast_facilitator"]) && $_POST["mast_facilitator"] == 1 ? "checked" :
                            ($profile->mast_facilitator == 1 ? "checked" : "") ?>> <?php echo __('yes'); ?> &nbsp;</label>
                <label>
                    <input type="radio" name="mast_facilitator" value="0"
                        <?php echo ((isset($_POST["mast_facilitator"]) && $_POST["mast_facilitator"] == 0) || ($profile->mast_facilitator == 0)) ? "checked" :
                            (!isset($_POST["mast_facilitator"]) ? "checked" : "") ?>> <?php echo __('no'); ?> &nbsp;</label>
            </div>
        </div>

        <div class="form-group">
            <label class="<?php echo isset($data["errors"]["org"]) ? "label_error" : "" ?>">
                <div class="profile_error">
                    <span><?php echo __('org'); ?>:</span>
                    <?php if (isset($data["errors"]["org"])):?>
                        <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["org"] ?>'></span>
                    <?php endif; ?>
                </div>
            </label>
            <div class="form-control">
                <label><input type="radio" name="org" value="Other" disabled
                        <?php echo isset($_POST["org"]) && $_POST["org"] == "Other" ? "checked" :
                            ($profile->org == "Other" ? "checked" : "") ?>> <?php echo __('other'); ?> &nbsp;</label>
                <label><input type="radio" name="org" value="WA EdServices" disabled
                        <?php echo isset($_POST["org"]) && $_POST["org"] == "WA EdServices" ? "checked" :
                            ($profile->org == "WA EdServices" ? "checked" : "") ?>> WA EdServices &nbsp;</label>
            </div>
        </div>

        <div class="form-group">
            <label class="<?php echo isset($data["errors"]["ref_person"]) ? "label_error" : "" ?>">
                <div class="profile_error">
                    <span><?php echo __('ref_person'); ?>:</span>
                    <?php if (isset($data["errors"]["ref_person"])):?>
                        <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["ref_person"] ?>'></span>
                    <?php endif; ?>
                </div>
            </label>
            <input class="form-control" type="text" name="ref_person"
                   value="<?php echo $_POST["ref_person"] ?? ($profile->ref_person ?? "") ?>" disabled>
        </div>

        <div class="form-group">
            <label class="<?php echo isset($data["errors"]["ref_email"]) ? "label_error" : "" ?>">
                <div class="profile_error">
                    <span><?php echo __('ref_email'); ?>:</span>
                    <?php if (isset($data["errors"]["ref_email"])):?>
                        <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["ref_email"] ?>'></span>
                    <?php endif; ?>
                </div>
            </label>
            <input type="text" class="form-control" name="ref_email"
                   value="<?php echo $_POST["ref_email"] ?? ($profile->ref_email ?? "") ?>" disabled>
        </div>
    </div>

    <div class="checker_section
        <?php echo isset($_POST["prefered_roles"]) && (in_array("translator", $_POST["prefered_roles"]) || in_array("checker", $_POST["prefered_roles"])) ? "shown" :
            (strpos($profile->prefered_roles, "translator") !== false
                || strpos($profile->prefered_roles, "checker") !== false ? "shown" : "") ?>">
        <h3><?php echo __('checker_skills'); ?></h3>

        <div class="form-group">
            <label class="<?php echo isset($data["errors"]["church_role"]) ? "label_error" : "" ?>">
                <div class="profile_error">
                    <span><?php echo __('church_role'); ?>:</span>
                    <?php if (isset($data["errors"]["church_role"])):?>
                        <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["church_role"] ?>'></span>
                    <?php endif; ?>
                </div>
            </label>
            <div class="form-control">
                <label><input type="checkbox" name="church_role[]" value="Elder"
                        <?php echo isset($_POST["church_role"]) && in_array("Elder", $_POST["church_role"]) ? "checked" :
                            (strpos($profile->church_role, "Elder") !== false ? "checked" : "") ?>> <?php echo __('elder'); ?> &nbsp;</label>
                <label><input type="checkbox" name="church_role[]" value="Bishop"
                        <?php echo isset($_POST["church_role"]) && in_array("Bishop", $_POST["church_role"]) ? "checked" :
                            (strpos($profile->church_role, "Bishop") !== false ? "checked" : "") ?>> <?php echo __('bishop'); ?> &nbsp;</label>
                <label><input type="checkbox" name="church_role[]" value="Pastor"
                        <?php echo isset($_POST["church_role"]) && in_array("Pastor", $_POST["church_role"]) ? "checked" :
                            (strpos($profile->church_role, "Pastor") !== false ? "checked" : "") ?>> <?php echo __('pastor'); ?> &nbsp;</label>
                <label><input type="checkbox" name="church_role[]" value="Teacher"
                        <?php echo isset($_POST["church_role"]) && in_array("Teacher", $_POST["church_role"]) ? "checked" :
                            (strpos($profile->church_role, "Teacher") !== false ? "checked" : "") ?>> <?php echo __('teacher'); ?> &nbsp;</label>
                <label><input type="checkbox" name="church_role[]" value="Denominational Leader"
                        <?php echo isset($_POST["church_role"]) && in_array("Denominational Leader", $_POST["church_role"]) ? "checked" :
                            (strpos($profile->church_role, "Denominational Leader") !== false ? "checked" : "") ?>> <?php echo __('denominational_leader'); ?> &nbsp;</label>
                <label><input type="checkbox" name="church_role[]" value="Seminary Professor"
                        <?php echo isset($_POST["church_role"]) && in_array("Seminary Professor", $_POST["church_role"]) ? "checked" :
                            (strpos($profile->church_role, "Seminary Professor") !== false ? "checked" : "") ?>> <?php echo __('seminary_professor'); ?> &nbsp;</label>
            </div>
        </div>

        <div class="form-group">
            <label><?php echo __('orig_langs'); ?>: </label>
            <div class="form-control">
                <label class="<?php echo isset($data["errors"]["hebrew_knwlg"]) ? "label_error" : "" ?>">
                    <div class="profile_error">
                        <span><?php echo __('hebrew_knwlg'); ?>:</span>
                        <?php if (isset($data["errors"]["hebrew_knwlg"])):?>
                            <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["hebrew_knwlg"] ?>'></span>
                        <?php endif; ?>
                    </div>
                </label> &nbsp;&nbsp;
                <label><input type="radio" name="hebrew_knwlg" value="0"
                        <?php echo isset($_POST["hebrew_knwlg"]) && $_POST["hebrew_knwlg"] == 0 ? "checked" :
                            ($profile->hebrew_knwlg == 0 ? "checked" : "") ?>> <?php echo __('none'); ?> &nbsp;</label>
                <label><input type="radio" name="hebrew_knwlg" value="1"
                        <?php echo isset($_POST["hebrew_knwlg"]) && $_POST["hebrew_knwlg"] == 1 ? "checked" :
                            ($profile->hebrew_knwlg == 1 ? "checked" : "") ?>> <?php echo __('limited'); ?> &nbsp;</label>
                <label><input type="radio" name="hebrew_knwlg" value="2"
                        <?php echo isset($_POST["hebrew_knwlg"]) && $_POST["hebrew_knwlg"] == 2 ? "checked" :
                            ($profile->hebrew_knwlg == 2 ? "checked" : "") ?>> <?php echo __('moderate'); ?> &nbsp;</label>
                <label><input type="radio" name="hebrew_knwlg" value="3"
                        <?php echo isset($_POST["hebrew_knwlg"]) && $_POST["hebrew_knwlg"] == 3 ? "checked" :
                            ($profile->hebrew_knwlg == 3 ? "checked" : "") ?>> <?php echo __('strong'); ?> &nbsp;</label>
                <label><input type="radio" name="hebrew_knwlg" value="4"
                        <?php echo isset($_POST["hebrew_knwlg"]) && $_POST["hebrew_knwlg"] == 4 ? "checked" :
                            ($profile->hebrew_knwlg == 4 ? "checked" : "") ?>> <?php echo __('expert'); ?> &nbsp;</label>
            </div>
            <br>
            <div class="form-control">
                <label class="<?php echo isset($data["errors"]["greek_knwlg"]) ? "label_error" : "" ?>">
                    <div class="profile_error">
                        <span><?php echo __('greek_knwlg'); ?>:</span>
                        <?php if (isset($data["errors"]["greek_knwlg"])):?>
                            <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["greek_knwlg"] ?>'></span>
                        <?php endif; ?>
                    </div>
                </label> &nbsp;&nbsp;
                <label><input type="radio" name="greek_knwlg" value="0"
                        <?php echo isset($_POST["greek_knwlg"]) && $_POST["greek_knwlg"] == 0 ? "checked" :
                            ($profile->greek_knwlg == 0 ? "checked" : "") ?>> <?php echo __('none'); ?> &nbsp;</label>
                <label><input type="radio" name="greek_knwlg" value="1"
                        <?php echo isset($_POST["greek_knwlg"]) && $_POST["greek_knwlg"] == 1 ? "checked" :
                            ($profile->greek_knwlg == 1 ? "checked" : "") ?>> <?php echo __('limited'); ?> &nbsp;</label>
                <label><input type="radio" name="greek_knwlg" value="2"
                        <?php echo isset($_POST["greek_knwlg"]) && $_POST["greek_knwlg"] == 2 ? "checked" :
                            ($profile->greek_knwlg == 2 ? "checked" : "") ?>> <?php echo __('moderate'); ?> &nbsp;</label>
                <label><input type="radio" name="greek_knwlg" value="3"
                        <?php echo isset($_POST["greek_knwlg"]) && $_POST["greek_knwlg"] == 3 ? "checked" :
                            ($profile->greek_knwlg == 3 ? "checked" : "") ?>> <?php echo __('strong'); ?> &nbsp;</label>
                <label><input type="radio" name="greek_knwlg" value="4"
                        <?php echo isset($_POST["greek_knwlg"]) && $_POST["greek_knwlg"] == 4 ? "checked" :
                            ($profile->greek_knwlg == 4 ? "checked" : "") ?>> <?php echo __('expert'); ?> &nbsp;</label>
            </div>
        </div>

        <div class="form-group">
            <label class="<?php echo isset($data["errors"]["education"]) ? "label_error" : "" ?>">
                <div class="profile_error">
                    <span><?php echo __('education'); ?>:</span>
                    <?php if (isset($data["errors"]["education"])):?>
                        <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["education"] ?>'></span>
                    <?php endif; ?>
                </div>
            </label>
            <div class="form-control">
                <label><input type="checkbox" name="education[]" value="BA"
                        <?php echo isset($_POST["education"]) && in_array("BA", $_POST["education"]) ? "checked" :
                            (strpos($profile->education, "BA") !== false ? "checked" : "") ?>> <?php echo __('ba_edu'); ?> &nbsp;</label>
                <label><input type="checkbox" name="education[]" value="MA"
                        <?php echo isset($_POST["education"]) && in_array("MA", $_POST["education"]) ? "checked" :
                            (strpos($profile->education, "MA") !== false ? "checked" : "") ?>> <?php echo __('ma_edu'); ?> &nbsp;</label>
                <label><input type="checkbox" name="education[]" value="PHD"
                        <?php echo isset($_POST["education"]) && in_array("PHD", $_POST["education"]) ? "checked" :
                            (strpos($profile->education, "PHD") !== false ? "checked" : "") ?>> <?php echo __('phd_edu'); ?> &nbsp;</label>
            </div>
        </div>

        <div class="form-group">
            <label class="<?php echo isset($data["errors"]["ed_area"]) ? "label_error" : "" ?>">
                <div class="profile_error">
                    <span><?php echo __('ed_area'); ?>:</span>
                    <?php if (isset($data["errors"]["ed_area"])):?>
                        <span class="mdi mdi-alert-circle-outline" data-toggle='tooltip' title='<?php echo $data["errors"]["ed_area"] ?>'></span>
                    <?php endif; ?>
                </div>
            </label>
            <div class="form-control">
                <label><input type="checkbox" name="ed_area[]" value="Theology"
                        <?php echo isset($_POST["ed_area"]) && in_array("Theology", $_POST["ed_area"]) ? "checked" :
                            (strpos($profile->ed_area, "Theology") !== false ? "checked" : "") ?>> <?php echo __('theology'); ?> &nbsp;</label>
                <label><input type="checkbox" name="ed_area[]" value="Pastoral Ministry"
                        <?php echo isset($_POST["ed_area"]) && in_array("Pastoral Ministry", $_POST["ed_area"]) ? "checked" :
                            (strpos($profile->ed_area, "Pastoral Ministry") !== false ? "checked" : "") ?>> <?php echo __('pastoral_ministry'); ?> &nbsp;</label>
                <label><input type="checkbox" name="ed_area[]" value="Bible Translation"
                        <?php echo isset($_POST["ed_area"]) && in_array("Bible Translation", $_POST["ed_area"]) ? "checked" :
                            (strpos($profile->ed_area, "Bible Translation") !== false ? "checked" : "") ?>> <?php echo __('bible_translation'); ?> &nbsp;</label>
                <label><input type="checkbox" name="ed_area[]" value="Exegetics"
                        <?php echo isset($_POST["ed_area"]) && in_array("Exegetics", $_POST["ed_area"]) ? "checked" :
                            (strpos($profile->ed_area, "Exegetics") !== false ? "checked" : "") ?>> <?php echo __('exegetics'); ?> &nbsp;</label>
            </div>
        </div>

        <div class="form-group">
            <label class="<?php echo isset($data["errors"]["ed_place"]) ? "label_error" : "" ?>"><?php echo __('ed_place'); ?>: </label>
            <input type="text" class="form-control" name="ed_place"
                   value="<?php echo $_POST["ed_place"] ?? ($profile->ed_place ?? "") ?>">
        </div>
    </div>

    <input type="hidden" name="csrfToken" value="<?php echo $data['csrfToken']; ?>" />

    <div class="save_profile_container unlinked">
        <button type="submit" name="submit" id="save_profile" class="btn btn-primary"><?php echo __('save'); ?></button>
    </div>
</form>

<div class="language_container">
    <div class="language_block">
        <div class="language-close glyphicon glyphicon-remove"></div>

        <label><?php echo __('select_language'); ?>: </label>
        <select class="form-control language" data-placeholder="<?php echo __('select_search_lang_option'); ?>">
            <option></option>
            <?php foreach ($languages as $lang):?>
                <option value="<?php echo $lang->langID; ?>">
                    <?php echo "[".$lang->langID."] " . $lang->langName .
                        ($lang->angName != "" && $lang->langName != $lang->angName ? " ( ".$lang->angName." )" : ""); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <br><br>

        <label><?php echo __('language_fluency'); ?>: </label>
        <div class="form-control">
            <label><input type="radio" class="fluency" name="" value="0" disabled> <?php echo __('none'); ?> &nbsp;</label>
            <label><input type="radio" class="fluency" name="" value="1" disabled> <?php echo __('moderate'); ?> &nbsp;</label>
            <label><input type="radio" class="fluency" name="" value="2" disabled> <?php echo __('strong'); ?> &nbsp;</label>
            <label><input type="radio" class="fluency" name="" value="3" disabled> <?php echo __('fluent'); ?> &nbsp;</label>
            <label><input type="radio" class="fluency" name="" value="4" disabled> <?php echo __('native'); ?> &nbsp;</label>
            <label><input type="radio" class="fluency" name="" value="5" disabled> <?php echo __('expert'); ?> &nbsp;</label>
        </div>
        <br>
        <button class="add_lang btn btn-primary" disabled><?php echo __("add_lang") ?></button>
    </div>
</div>

<link href="<?php echo template_url("css/chosen.min.css?v=2")?>" type="text/css" rel="stylesheet" />
<script src="<?php echo template_url("js/chosen.jquery.min.js?v=2")?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(".profile_form select").chosen();
    });
</script>
