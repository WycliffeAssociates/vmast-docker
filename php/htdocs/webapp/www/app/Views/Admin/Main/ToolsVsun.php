<div id="tools">
    <ul class="nav nav-tabs">
        <li role="presentation" class="url_tab">
            <a href="/admin/tools"><?php echo __("source") ?></a>
        </li>
        <li role="presentation" class="url_tab active">
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
            <div class="sun_font_tools">
                <div class="tools_title"><?php echo __("sun_font_uploader"); ?></div>

                <div class="form-group">
                    <label for="sun_upload" class="sr-only">Upload</label>
                    <input type="file" accept=".woff" class="form-control" id="sun_upload" placeholder="<?php echo __("upload_sun_font") ?>" value="">
                </div>

                <button class="btn btn-warning"><?php echo __("upload"); ?></button>
                <span class="glyphicon glyphicon-exclamation-sign"
                      data-toggle="tooltip"
                      data-placement="auto bottom"
                      title="<?php echo __("font_uploader_tooltip") ?>"></span>
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>

            <div class="saildict tools">
                <div class="tools_title"><?php echo __("sail_dictionary_editor"); ?></div>

                <div class="saildict_upload tools">
                    <div class="form-group">
                        <label for="saildic_upload" class="sr-only">Upload</label>
                        <input type="file" accept=".csv" class="form-control" id="saildic_upload" placeholder="<?php echo __("upload_saildic") ?>" value="">
                    </div>

                    <button class="btn btn-warning"><?php echo __("upload"); ?></button>
                    <span class="glyphicon glyphicon-exclamation-sign"
                          data-toggle="tooltip"
                          data-placement="auto bottom"
                          title="<?php echo __("saildic_uploader_tooltip") ?>"></span>
                    <img src="<?php echo template_url("img/loader.gif") ?>">
                </div>

                <div class="sail_filter tools">
                    <div class="form-group">
                        <label for="sailfilter" class="sr-only">Filter</label>
                        <input type="text" class="form-control" id="sailfilter" placeholder="<?php echo __("filter_by_word") ?>" value="">
                        <label for="sailfilter_global" style="margin-top: 10px;">
                            <input type="checkbox" class="" id="sailfilter_global" value="">
                            <span style="font-size: 16px; font-weight: normal;">full text search</span>
                        </label>
                    </div>
                </div>

                <div class="sail_create form-inline sun_content">
                    <div class="form-group">
                        <label for="sailword" class="sr-only">Word</label>
                        <input type="text" class="form-control input" id="sailword" placeholder="<?php echo __("sail_enter_word") ?>" value="">
                    </div>
                    <div class="form-group" style="">
                        <label for="sailsymbol" class="sr-only">Symbol</label>
                        <input type="text" class="form-control input" id="sailsymbol" placeholder="<?php echo __("sail_enter_symbol") ?>" value="">
                    </div>
                    <button class="btn btn-primary add_word" style="margin-top: -5px"><?php echo __("add") ?></button>
                    <img id="sail_create_loader" src="<?php echo template_url("img/loader.gif") ?>" style="margin-top: -5px">
                </div>

                <div class="sail_list tools">
                    <ul>
                        <?php foreach ($data["saildict"] as $word): ?>
                            <li class="sun_content" id="<?php echo $word->word ?>">
                                <div class="tools_delete_word glyphicon glyphicon-remove" title="<?php echo __("delete") ?>">
                                    <img src="<?php echo template_url("img/loader.gif") ?>">
                                </div>

                                <div class="sail_word"><?php echo $word->word ?></div>
                                <div class="sail_symbol"><?php echo $word->symbol ?></div>
                                <input type="text" value="<?php echo $word->symbol ?>" />
                                <div class="clear"></div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="copied_tooltip" style="font-size: 16px"><?php echo __("copied_tip") ?></div>
            </div>
        </div>
    </div>
</div>
