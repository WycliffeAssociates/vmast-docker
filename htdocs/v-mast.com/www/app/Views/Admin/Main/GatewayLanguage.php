<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("projects") ?></h1>
    </div>

    <h3 style="margin-left: 10px"><?php echo __("choose_project") ?></h3>

    <div class="form-inline dt-bootstrap no-footer">
        <div class="row">
            <div class="col-sm-5">&nbsp;</div>
            <?php if ($isGlAdmin): ?>
            <div class="add-event-btn col-sm-6">
                <?php echo __("create_project") ?>
                <button id="crepr" class="btn btn-primary glyphicon glyphicon-plus"></button>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-hover" role="grid">
                    <thead>
                    <tr>
                        <th><?php echo __("target_lang") ?></th>
                        <th><?php echo __("gateway_language") ?></th>
                        <th><?php echo __("project") ?></th>
                        <th><?php echo __("source") ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($projects as $project):?>
                        <tr>
                            <td>
                                <a href="/admin/project/<?php echo $project->projectID ?>">
                                    <?php echo "[" . $project->targetLanguage->langID . "] " .
                                        $project->targetLanguage->langName .
                                        ($project->targetLanguage->langName != $project->targetLanguage->angName
                                            && $project->targetLanguage->angName != ""
                                                ? " (" . $project->targetLanguage->angName . ")" : "") ?>
                                </a>
                            </td> 
                            <td><?php echo "[" . $project->gwLang . "] " . 
                                $project->gatewayLanguage->language->langName .
                                ($project->gatewayLanguage->language->langName != $project->gatewayLanguage->language->angName
                                    && $project->gatewayLanguage->language->angName != ""
                                        ? " (" . $project->gatewayLanguage->language->angName . ")" : "") ?></td>
                            <td><?php echo __($project->bookProject) ?></td>
                            <td><?php echo __($project->sourceBible). " (".$project->sourceLangID.")"  ?></td>
                            <td>
                                <?php if ($isGlAdmin): ?>
                                <button
                                        data-projectid="<?php echo $project->projectID ?>"
                                        class="btn btn-success editProject"><?php echo __("edit") ?></button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="sub-content form-panel">
    <div class="create-sub-content panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("create_project") ?></h1>
            <span class="panel-close glyphicon glyphicon-remove"></span>
        </div>

        <div class="page-content panel-body">
            <div class="subErrors"></div>

            <form action="/admin/rpc/create_project" method="post" id="project">

                <div class="sub-parent">

                    <div class="form-group">
                        <label for="project_admins"><?php echo __('project_admins'); ?></label>
                        <select class="form-control" name="project_admins[]" id="project_admins" multiple>
                            <option></option>
                        </select>
                    </div>

                    <div class="sub-form">
                        <div class="sub-form-left">
                            <div class="form-group">
                                <label for="projectMode"><?php echo __('project_mode'); ?></label>
                                <select name="projectMode" id="projectMode" class="form-control"
                                        data-placeholder="<?php echo __('choose_project_mode'); ?>">
                                    <option value=""></option>
                                    <option value="bible"><?php echo __("bible_mode") ?></option>
                                    <option value="tn"><?php echo __("notes_mode") ?></option>
                                    <option value="tq"><?php echo __("questions_mode") ?></option>
                                    <option value="tw"><?php echo __("words_mode") ?></option>
                                    <option value="odb"><?php echo __("odb_mode") ?></option>
                                    <option value="rad"><?php echo __("radio_mode") ?></option>
                                    <option value="obs"><?php echo __("obs_mode") ?></option>
                                    <option value="bc"><?php echo __("bc_mode") ?></option>
                                    <option value="bca"><?php echo __("bca_mode") ?></option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subGwLangs"><?php echo __('gateway_language'); ?></label>
                                <select class="form-control" id="subGwLangs" name="subGwLangs"
                                        data-placeholder="<?php echo __('choose_gw_lang'); ?>">
                                    <option value=""></option>
                                    <option value="<?php echo $gatewayLanguage->gwLang ."|".$gatewayLanguage->glID ?>">
                                        <?php echo "[".$gatewayLanguage->gwLang."] " . $gatewayLanguage->language->langName .
                                            ($gatewayLanguage->language->langName != $gatewayLanguage->language->angName
                                            && $gatewayLanguage->language->angName != ""
                                                ? " ( ".$gatewayLanguage->language->angName." )" : ""); ?>
                                    </option>
                                </select>
                            </div>

                            <div class="form-group subGwLangsContainer">
                                <label for="targetLangs"><?php echo __('target_lang'); ?></label>
                                <select class="form-control" id="targetLangs" name="targetLangs"
                                        data-placeholder="<?php echo __('choose_target_lang'); ?>">
                                    <option value=""></option>
                                </select>
                                <img class="subGwLoader" width="24px" src="<?php echo template_url("img/loader.gif") ?>">
                            </div>

                            <div class="form-group sourceTranslation">
                                <label for="sourceTranslation"><?php echo __('book_project'); ?></label>
                                <select name="sourceTranslation" id="sourceTranslation" class="form-control"
                                        data-placeholder="<?php echo __('choose_source_trans'); ?>">
                                    <option value=""></option>
                                    <?php foreach ($sources as $source): ?>
                                        <?php if(in_array($source->slug, ["tn","tq","tw","udb"])) continue; ?>
                                        <option value="<?php echo $source->slug . "|" . $source->langID; ?>">
                                            <?php echo "[".$source->langID."] "
                                                . $source->langName . " - "
                                                . $source->name . " [".$source->slug."]" ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="add_custom_src">
                                    <a href="/admin/tools"><?php echo __("add_custom_src") ?></a>
                                </div>
                            </div>
                        </div>

                        <div class="sub-form-right">
                            <div class="form-group projectType">
                                <label for="projectType"><?php echo __('project_type'); ?></label>
                                <select name="projectType" id="projectType" class="form-control"
                                        data-placeholder="<?php echo __('choose_project_type'); ?>">
                                    <option value=""></option>
                                    <option value="ulb"><?php echo __("ulb") ?></option>
                                    <option value="sun"><?php echo __("sun") ?></option>
                                </select>
                            </div>

                            <div class="form-group sourceTools hidden">
                                <label for="sourceTools"></label>
                                <select name="sourceTools" id="sourceTools" class="form-control" data-placeholder="">
                                    <option value=""></option>
                                    <?php foreach ($gwLangs as $lang): ?>
                                        <option value="<?php echo $lang->langID ?>">
                                            <?php echo "[".$lang->langID."] " . $lang->langName .
                                                ($lang->langName != $lang->angName && $lang->angName != ""
                                                    ? " ( ".$lang->angName." )" : ""); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <hr>
                            <div class="form-group toolsTn">
                                <label for="toolsTn"><?php echo __("tn") ?></label>
                                <select name="toolsTn" id="toolsTn" class="form-control" data-placeholder="">
                                    <option value=""></option>
                                    <?php foreach ($gwLangs as $lang): ?>
                                        <option value="<?php echo $lang->langID ?>">
                                            <?php echo "[".$lang->langID."] " . $lang->langName .
                                                ($lang->langName != $lang->angName && $lang->angName != ""
                                                    ? " ( ".$lang->angName." )" : ""); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group toolsTq">
                                <label for="toolsTq"><?php echo __("tq") ?></label>
                                <select name="toolsTq" id="toolsTq" class="form-control" data-placeholder="">
                                    <option value=""></option>
                                    <?php foreach ($gwLangs as $lang): ?>
                                        <option value="<?php echo $lang->langID ?>">
                                            <?php echo "[".$lang->langID."] " . $lang->langName .
                                                ($lang->langName != $lang->angName && $lang->angName != ""
                                                    ? " ( ".$lang->angName." )" : ""); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group toolsTw">
                                <label for="toolsTw"><?php echo __("tw") ?></label>
                                <select name="toolsTw" id="toolsTw" class="form-control" data-placeholder="">
                                    <option value=""></option>
                                    <?php foreach ($gwLangs as $lang): ?>
                                        <option value="<?php echo $lang->langID ?>">
                                            <?php echo "[".$lang->langID."] " . $lang->langName .
                                                ($lang->langName != $lang->angName && $lang->angName != ""
                                                    ? " ( ".$lang->angName." )" : ""); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group toolsBc">
                                <label for="toolsBc"><?php echo __("bc") ?></label>
                                <select name="toolsBc" id="toolsBc" class="form-control" data-placeholder="">
                                    <option value=""></option>
                                    <?php foreach ($gwLangs as $lang): ?>
                                        <option value="<?php echo $lang->langID ?>">
                                            <?php echo "[".$lang->langID."] " . $lang->langName .
                                                ($lang->langName != $lang->angName && $lang->angName != ""
                                                    ? " ( ".$lang->angName." )" : ""); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="act" id="projectAction" value="create">
                <input type="hidden" name="projectID" id="projectID">

                <div class="form-group" style="padding-top: 20px;">
                    <button type="submit" name="project" class="btn btn-primary"><?php echo __('create'); ?></button>
                    <img class="projectLoader" width="24px" src="<?php echo template_url("img/loader.gif") ?>">
                </div>
            </form>
        </div>
    </div>
</div>

<link href="<?php echo template_url("css/chosen.min.css?v=2")?>" type="text/css" rel="stylesheet" />
<script src="<?php echo template_url("js/chosen.jquery.min.js?v=2")?>"></script>
<script src="<?php echo template_url("js/ajax-chosen.min.js")?>"></script>