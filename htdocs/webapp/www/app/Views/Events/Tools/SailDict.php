<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 1/17/19
 * Time: 5:11 PM
 */

if(!empty($data["saildict"])): ?>
<div class="ttools_panel saildict_tool panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("sail_dictionary") ?></h1>
<span class="panel-close glyphicon glyphicon-remove" data-tool="saildict"></span>
</div>

<div class="ttools_content sun_content saildict page-content panel-body">
    <div class="sail_filter">
        <div class="form-group">
            <label for="sailfilter" class="sr-only">Filter</label>
            <input type="text" class="form-control input-lg" id="sailfilter" placeholder="<?php echo __("filter_by_word") ?>" value="">
            <label for="sailfilter_global"><input type="checkbox" class="" id="sailfilter_global" value=""> <span style="font-size: 16px">full text search</span></label>
        </div>
    </div>
    <div class="sail_list">
        <ul>
            <?php foreach ($data["saildict"] as $word): ?>
                <li id="<?php echo $word->word ?>" title="<?php echo __("copy_symbol_tip") ?>">
                    <div class="sail_word"><?php echo $word->word ?></div>
                    <div class="sail_symbol"><?php echo $word->symbol ?></div>
                    <input type="text" value="<?php echo $word->symbol ?>" />
                    <div class="clear"></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<div class="copied_tooltip"><?php echo __("copied_tip") ?></div>
</div>
<?php endif; ?>