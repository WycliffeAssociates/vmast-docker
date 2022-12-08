<div id="tools">
    <ul class="nav nav-tabs">
        <li role="presentation" class="url_tab active">
            <a href="/admin/tools"><?php echo __("source") ?></a>
        </li>
        <li role="presentation" class="url_tab">
            <a href="/admin/tools/vsun"><?php echo __("sun_tools") ?></a>
        </li>
        <li role="presentation" class="url_tab">
            <a href="/admin/tools/faq"><?php echo __("faq_tools") ?></a>
        </li>
        <li role="presentation" class="url_tab">
            <a href="/admin/tools/news"><?php echo __("news") ?></a>
        </li>
        <li role="presentation" class="url_tab">
            <a href="/admin/tools/common"><?php echo __("common") ?></a>
        </li>
    </ul>

    <div id="tools_content" class="tools_content shown">
        <div class="tools_left">
            <div class="source_create">
                <div class="tools_title"><?php echo __("create_source"); ?></div>

                <div class="form-group">
                    <label for="src_language" class=""><?php echo __("tools_src_language"); ?>:</label>
                    <select class="form-control" id="src_language" name="src_language">
                        <option value="" class="hidden"><?php echo __('select_src_language'); ?></option>
                        <?php foreach ($langs as $lang): ?>
                        <option value="<?php echo $lang->langID ?>">
                            <?php echo "[".$lang->langID."] " . $lang->langName .
                                ($lang->langName != $lang->angName && $lang->angName != ""
                                    ? " ( ".$lang->angName." )" : ""); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="src_type" class=""><?php echo __("tools_src_type"); ?>:</label>
                    <select class="form-control" id="src_type" name="src_type">
                        <option value="" class="hidden"><?php echo __('select_src_type'); ?></option>
                        <?php foreach ($sourceTypes as $srcType): ?>
                            <option value="<?php echo $srcType->slug . "|" . $srcType->name ?>">
                                <?php echo "[" . $srcType->slug . "]" . " " .
                                    (__($srcType->slug) == $srcType->slug ? $srcType->name : __($srcType->slug)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="add_custom_src"><?php echo __("add_custom_src") ?></div>
                </div>

                <div class="form-group custom_src_type">
                    <label for="src_slug" class=""><?php echo __("tools_src_slug"); ?>:
                        <input type="text" class="form-control" id="src_slug" name="src_slug"
                               placeholder="<?php echo __('enter_src_slug'); ?>" value="">
                    </label>
                    <label for="src_name" class=""><?php echo __("tools_src_name"); ?>:
                        <input type="text" class="form-control" id="src_name" name="src_name"
                               placeholder="<?php echo __('enter_src_name'); ?>" value="">
                    </label>
                    <button class="btn btn-warning"><?php echo __("add"); ?></button>
                </div>

                <button class="btn btn-warning src_create"><?php echo __("create"); ?></button>
                <img class="src_loader" src="<?php echo template_url("img/loader.gif") ?>">
            </div>
        </div>
        <div class="tools_middle">
            <div class="source_upload">
                <div class="tools_title"><?php echo __("upload_source"); ?></div>

                <div class="form-group">
                    <label for="src_upload" class=""><?php echo __("tools_src_file"); ?>:</label>
                    <label for="src_upload" class="sr-only">Upload</label>
                    <input type="file" accept=".zip,.orature,.tstudio" class="form-control" id="src_upload"
                           placeholder="<?php echo __("tools_src_upload") ?>" value="">
                </div>

                <button class="btn btn-warning src_upload"><?php echo __("upload"); ?></button>
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
        </div>
        <div class="tools_right">
            <div class="source_update">
                <div class="tools_title"><?php echo __("update_source"); ?></div>

                <div class="form-group">
                    <label for="src" class=""><?php echo __("tools_src_type"); ?>:</label>
                    <select class="form-control" id="src" name="src">
                        <option value="" class="hidden"><?php echo __('select_src_type'); ?></option>
                        <?php foreach ($sources as $source): ?>
                            <option value="<?php echo $source->langID . "|" . $source->slug ?>">
                                <?php
                                echo "[" . $source->langID . "_" . $source->slug . "]"
                                    . " " . ($source->language ? $source->language->langName : "")
                                    . ($source->language && $source->language->langName != $source->language->angName && $source->language->angName != ""
                                        ? " ( ".$source->language->angName." )" : "")
                                    . " - " . $source->name
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button class="btn btn-warning src_update"><?php echo __("update"); ?></button>
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("select").chosen({ width: '100%' });
    });
</script>

<link href="<?php echo template_url("css/chosen.min.css?v=2")?>" type="text/css" rel="stylesheet" />
<script src="<?php echo template_url("js/chosen.jquery.min.js?v=2")?>"></script>
<script src="<?php echo template_url("js/ajax-chosen.min.js")?>"></script>
