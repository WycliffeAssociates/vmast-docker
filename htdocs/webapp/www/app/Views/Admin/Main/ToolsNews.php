<div id="tools">
    <ul class="nav nav-tabs">
        <li role="presentation" class="url_tab">
            <a href="/admin/tools"><?php use Helpers\Constants\Projects;

                echo __("source") ?></a>
        </li>
        <li role="presentation" class="url_tab">
            <a href="/admin/tools/vsun"><?php echo __("sun_tools") ?></a>
        </li>
        <li role="presentation" class="url_tab">
            <a href="/admin/tools/faq"><?php echo __("faq_tools") ?></a>
        </li>
        <li role="presentation" class="url_tab active">
            <a href="/admin/tools/news"><?php echo __("news") ?></a>
        </li>
        <li role="presentation" class="url_tab">
            <a href="/admin/tools/common"><?php echo __("common") ?></a>
        </li>
    </ul>

    <div id="tools_content" class="tools_content shown">
        <div class="tools_left">
            <div class="create_news">
                <div class="tools_title"><?php echo __("create_news"); ?></div>

                <div class="form-group">
                    <label for="title" class=""><?php echo __("tools_news_title"); ?>:</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="<?php echo __('enter_news_text'); ?>" value="">
                </div>

                <div class="form-group">
                    <label for="category" class=""><?php echo __("tools_news_category"); ?>:</label>
                    <select class="form-control" id="category" name="category">
                        <option value="" hidden><?php echo __('select_news_category'); ?></option>
                        <option value="common"><?php echo __("common") ?></option>
                        <?php foreach (Projects::list() as $project): ?>
                        <option value="<?php echo $project ?>"><?php echo __($project) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="text" class=""><?php echo __("tools_news_text"); ?>:</label>
                    <textarea rows="4" class="form-control textarea" id="text" name="text" placeholder="<?php echo __('enter_news_text'); ?>"></textarea>
                </div>

                <button class="btn btn-warning"><?php echo __("create"); ?></button>
                <img src="<?php echo template_url("img/loader.gif") ?>">
            </div>
        </div>
    </div>
</div>
