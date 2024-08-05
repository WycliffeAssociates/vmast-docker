<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 1/17/19
 * Time: 5:11 PM
 */

if ($data["rubric"]): ?>
    <div class="ttools_panel rubric_tool panel panel-default">
        <div class="panel-heading">
            <h1 class="panel-title"><?php echo __("show_rubric") ?></h1>
            <span class="panel-close glyphicon glyphicon-remove" data-tool="rubric"></span>
        </div>

        <div class="ttools_content page-content panel-body">
            <ul class="nav nav-tabs nav-justified read_rubric_tabs">
                <li role="presentation" id="tab_orig" class="active"><a href="#"><?php echo $data["rubric"]->language->langName ?></a></li>
                <li role="presentation" id='tab_eng'><a href="#">English</a></li>
            </ul>
            <div class="read_rubric_qualities">
                <br>
                <?php $tr=1; foreach($data["rubric"]->qualities as $quality): ?>
                    <div class="read_rubric_quality orig" dir="<?php echo $data['rubric']->language->direction ?>">
                        <?php echo !empty($quality->content) ? sprintf('%01d', $tr) . ". " .  $quality->content : ""; ?>
                    </div>
                    <div class="read_rubric_quality eng">
                        <?php echo !empty($quality->eng) ? sprintf('%01d', $tr) . ". " . $quality->eng : ""; ?>
                    </div>

                    <div class="read_rubric_defs">
                        <?php $df=1; foreach($quality->defs as $def): ?>
                            <div class="read_rubric_def orig" dir="<?php echo $data['rubric']->language->direction ?>">
                                <?php echo !empty($def->content) ? sprintf('%01d', $df) . ". " . $def->content : ""; ?>
                            </div>
                            <div class="read_rubric_def eng">
                                <?php echo !empty($def->eng) ? sprintf('%01d', $df) . ". " . $def->eng : ""; ?>
                            </div>

                            <div class="read_rubric_measurements">
                                <?php $ms=1; foreach($def->measurements as $measurement): ?>
                                    <div class="read_rubric_measurement orig" dir="<?php echo $data['rubric']->language->direction ?>">
                                        <?php echo !empty($measurement->content) ? sprintf('%01d', $ms) . ". " . $measurement->content : ""; ?>
                                    </div>
                                    <div class="read_rubric_measurement eng">
                                        <?php echo !empty($measurement->eng) ? sprintf('%01d', $ms) . ". " . $measurement->eng : ""; ?>
                                    </div>
                                    <?php $ms++; endforeach; ?>
                            </div>
                            <?php $df++; endforeach; ?>
                    </div>
                    <?php $tr++; endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>