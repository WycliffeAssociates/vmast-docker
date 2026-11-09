<?php
use \Helpers\Session;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("gateway_languages") ?></h1>
    </div>

    <div class="form-inline dt-bootstrap no-footer">
        <div class="row">
            <div class="col-sm-6">&nbsp;</div>
            <?php if (Session::get("isSuperAdmin")): ?>
            <div class="add-event-btn col-sm-6">
                <?php echo __("create_gateway_language") ?>
                <button id="cregl" class="btn btn-primary glyphicon glyphicon-plus"></button>
            </div>
            <?php endif; ?>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <table class="table table-bordered table-hover" role="grid">
                    <thead>
                        <tr>
                            <th><?php echo __("gateway_language") ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($gatewayLanguages as $gatewayLanguage):?>
                        <tr>
                            <td>
                                <a href="/admin/gateway_language/<?php echo $gatewayLanguage->glID ?>">
                                    <?php echo "[" . $gatewayLanguage->gwLang . "] "
                                        . $gatewayLanguage->language->langName
                                        . ($gatewayLanguage->language->angName != $gatewayLanguage->language->langName
                                            && $gatewayLanguage->language->angName != ""
                                                ? " (" . $gatewayLanguage->language->angName . ")"
                                                : "") ?></a>
                            </td>
                            <td width="70">
                                <?php if (Session::get("isSuperAdmin")): ?>
                                <button data-id="<?php echo $gatewayLanguage->glID ?>"
                                        class="btn btn-warning gl_edit">
                                    <?php echo __("edit") ?>
                                </button>
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

<div class="main-content form-panel">
    <div class="create-main-content panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("gateway_language") ?></h1>
            <span class="panel-close glyphicon glyphicon-remove"></span>
        </div>

        <div class="page-content panel-body">
            <div class="errors"></div>

            <form action="/admin/rpc/create_gateway_language" method="post" id="gatewayLanguage" style="width: 400px;">
                <div class="form-group">
                    <select class="form-control" id="gwLang" name="gwLang" data-placeholder="<?php echo __("choose_gw_lang") ?>">
                        <option value=""></option>
                        <?php foreach ($gwLangs as $gwLang):?>
                        <option value="<?php echo $gwLang->langID; ?>">
                            <?php echo "[".$gwLang->langID."] " . $gwLang->langName .
                                ($gwLang->langName != $gwLang->angName && $gwLang->angName != ""
                                    ? " ( ".$gwLang->angName." )" : ""); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" name="gatewayLanguage" class="btn btn-primary"><?php echo __('create'); ?></button>
                <img class="gatewayLanguageLoader" width="24px" src="<?php echo template_url("img/loader.gif") ?>">
            </form>
        </div>
    </div>
</div>


<div class="admins-content form-panel">
    <div class="edit-admins-content panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("gl_admins") ?></h1>
            <span class="panel-close glyphicon glyphicon-remove"></span>
        </div>

        <div class="page-content panel-body">
            <form action="/admin/rpc/edit_gl_admins" method="post" id="gatewayLanguageAdmins" style="width: 400px;">
                <div class="form-group">
                    <select class="form-control" name="gl_admins[]" id="gl_admins" multiple>
                        <option></option>
                    </select>
                </div>

                <input type="hidden" name="glID" id="glID" value="">

                <button type="submit" name="gatewayLanguageAdmins" class="btn btn-primary"><?php echo __('save'); ?></button>
                <img class="gatewayLanguageLoader" width="24px" src="<?php echo template_url("img/loader.gif") ?>">
            </form>
        </div>
    </div>
</div>

<link href="<?php echo template_url("css/chosen.min.css?v=2")?>" type="text/css" rel="stylesheet" />
<script src="<?php echo template_url("js/chosen.jquery.min.js?v=2")?>"></script>
<script src="<?php echo template_url("js/ajax-chosen.min.js")?>"></script>