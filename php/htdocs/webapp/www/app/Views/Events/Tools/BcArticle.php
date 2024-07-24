<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 1/17/19
 * Time: 5:51 PM
 */

if(!empty($data["article"])): ?>
<div class="ttools_panel bc_article_tool panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("bc") ?></h1>
        <span class="panel-close glyphicon glyphicon-remove" data-tool="bc_article"></span>
    </div>

    <div class="ttools_content page-content panel-body">
        <div class="bc_article_contents">
            <?php if(isset($data["article"])): ?>
                <?php echo $data["article"] ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>