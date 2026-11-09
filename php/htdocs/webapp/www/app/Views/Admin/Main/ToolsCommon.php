<div id="tools">
    <ul class="nav nav-tabs">
        <li role="presentation" class="url_tab">
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
        <li role="presentation" class="url_tab active">
            <a href="/admin/tools/common"><?php echo __("common") ?></a>
        </li>
    </ul>

    <div id="tools_content" class="tools_content shown">
        <div class="tools_left">
            <div class="update_langs">
                <div class="tools_title"><?php echo __("update_lang_db"); ?></div>
                <div class="form-group">
                    <label>Language Names Url:</label>
                    <input type="text" name="url" class="form-control" value="<?php echo $data["langNamesUrl"] ?>" />
                </div>
                <div class="form-group">
                    <label><input class="download_again" type="checkbox"> Download again</label>
                </div>
                <button class="btn btn-warning"><?php echo __("update"); ?></button>
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>

            <hr>

            <div class="update_catalog">
                <div class="tools_title"><?php echo __("update_src_catalog"); ?></div>
                <!--<div class="form-group">
                    <label>Catalog Url:</label>
                    <input type="text" name="url" class="form-control" value="<?php /*echo $data["catalogUrl"] */?>" />
                </div>-->
                <div class="form-group">
                    <label><input class="download_again" type="checkbox"> Download again</label>
                </div>
                <button class="btn btn-warning"><?php echo __("update"); ?></button>
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>

            <hr>

            <div class="clear_cache">
                <div class="tools_title"><?php echo __("clear_cache"); ?></div>
                <button class="btn btn-warning"><?php echo __("update"); ?></button>
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
        </div>

        <div class="tools_right">
            <div class="create_users">
                <div class="tools_title"><?php echo __("create_multiple_users"); ?></div>

                <div class="form-group">
                    <label for="amount" class=""><?php echo __("tools_quantity_members"); ?>:</label>
                    <input type="text" class="form-control" id="amount" name="amount" placeholder="<?php echo __('enter_value'); ?>" value="">
                </div>

                <div class="form-group">
                    <label for="langs" class=""><?php echo __("tools_member_language"); ?>:</label>
                    <input type="text" class="form-control" id="langs" name="langs" placeholder="<?php echo __('enter_lang_codes'); ?>" value="">
                </div>

                <div class="form-group">
                    <label for="password" class=""><?php echo __("password"); ?>:</label>
                    <input type="text" class="form-control" id="password" name="password" placeholder="<?php echo __('enter_value'); ?>" value="">
                </div>

                <button class="btn btn-warning"><?php echo __("create"); ?></button>
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
        </div>
    </div>
</div>
