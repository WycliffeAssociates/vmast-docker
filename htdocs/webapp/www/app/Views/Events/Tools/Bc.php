<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 1/17/19
 * Time: 5:51 PM
 */

if(!empty($data["commentaries"])): ?>
<div class="ttools_panel bc_tool panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("bc") ?></h1>
        <span class="panel-close glyphicon glyphicon-remove" data-tool="bc"></span>
    </div>

    <div class="ttools_content page-content panel-body">
        <div class="bc_contents">
            <?php if(isset($data["commentaries"])): ?>
                <?php echo $data["commentaries"] ?>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>