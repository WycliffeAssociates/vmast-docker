<div class="members_content shown" id="all_members_content">
    <div class="members_filter">
        <form id="membersFilter">
            <div class="mems_filter_item filter_title"><?php echo __("filter") ?>:</div>
            <div class="mems_filter_item filter_search">
                <input name="name" class="form-control" type="text" value="" placeholder="<?php echo __("search_name_filter") ?>" size="28">
            </div>
            <div class="mems_filter_item filter_roles">
                <div><label for="filterTr"><input name="role" id="filterTr" type="radio" value="translators"> <?php echo __("translators") ?></label></div>
                <div><label for="filterFc"><input name="role" id="filterFc" type="radio" value="facilitators"> <?php echo __("facilitators") ?></label></div>
                <div><label for="filterAll"><input name="role" id="filterAll" type="radio" value="all" checked> <?php echo __("all_mems") ?></label></div>
            </div>
            <div class="mems_filter_item filter_lang">
                <select class="mems_language" name="language" data-placeholder="<?php echo __('select_language'); ?>">
                    <option></option>
                    <?php foreach ($data["languages"] as $lang):?>
                        <option value="<?php echo $lang->langID; ?>"><?php echo "[".$lang->langID."] " . $lang->langName . ($lang->langName != $lang->angName ? " ( ".$lang->angName." )" : ""); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mems_filter_item filter_apply">
                <button class="btn btn-success"><?php echo __("apply_filter") ?></button>
            </div>
            <div class="mems_filter_item filter_clear">
                <button class="btn btn-danger"><?php echo __("clear_filter") ?></button>
            </div>
            <div class="mems_filter_item filter_loader">
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
            <input type="hidden" class="filter_page" autocomplete="off" name="page" value="2">
            <div class="clear"></div>
        </form>
    </div>

    <div class="panel panel-default" id="all_members_table" class="<?php echo $data["count"] <= 0 ? "hidden" : "" ?>">
        <div class="dt-bootstrap no-footer">
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-hover" role="grid">
                        <thead>
                        <tr>
                            <th><?php echo __("userName") ?></th>
                            <th><?php echo __("name") ?></th>
                            <th><?php echo __("Email") ?></th>
                            <th><?php echo __("project") ?></th>
                            <th><?php echo __("proj_lang_public") ?></th>
                            <th><?php echo __("profile_message") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($data["members"] as $member):?>
                            <tr>
                                <td><a href="/members/profile/<?php echo $member->memberID ?>"><?php echo $member->userName ?></a></td>
                                <td><?php echo $member->firstName . " " . $member->lastName ?></td>
                                <td><?php echo $member->email ?></td>
                                <td>
                                    <?php $projects = array_map(function ($elm) {
                                        return __($elm);
                                    }, (array)json_decode($member->projects, true)) ?>
                                    <?php echo join(", ", $projects) ?>
                                </td>
                                <td>
                                    <?php if($member->proj_lang): ?>
                                        <?php echo "[".$member->langID."] " . $member->langName .
                                            ($member->angName != "" && $member->angName != $member->langName ? " (".$member->angName.")" : "") ?>
                                    <?php endif; ?>
                                </td>
                                <td><input type='checkbox' <?php echo $member->complete ? "checked" : "" ?> disabled></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Show "more" button if more than one page with 50 rows -->
    <?php if($data["count"] > sizeof($data["members"])): ?>
        <div id="search_more"><?php echo __("search_more"); ?></div>
    <?php endif; ?>
</div>

<link href="<?php echo template_url("css/chosen.min.css?v=2")?>" type="text/css" rel="stylesheet" />
<script src="<?php echo template_url("js/chosen.jquery.min.js?v=2")?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("select.mems_language").chosen();
    });
</script>