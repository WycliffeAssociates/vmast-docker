<?php

use Helpers\Constants\InputMode;

$addUri = $data["inputMode"] != InputMode::NORMAL ? "-" . $data["inputMode"] : "";
?>

<div id="translator_contents" class="row panel-body">
    <div class="row main_content_header">
        <div class="action_type_container">
            <div class="demo_title"><?php echo __("demo") . " (".__($data["inputMode"]).")" ?></div>
        </div>
        <div class="main_content_title">
            <div><?php echo __("step_num", ["step_number" => 1]) . ": " . __("multi-draft_input_mode")?></div>
        </div>
    </div>

    <div class="main_content">
        <form action="" method="post" id="main_form">
            <div class="main_content_text" style="padding-left: 15px">

                <h4>Papuan Malay - <?php echo __("ulb") ?> - <?php echo __("new_test") ?> - <span class='book_name'>2 Timothy 2:1-26</span></h4>

                <div class="source_mode">
                    <label>
                        <?php echo __("show_source") ?>
                        <input type="checkbox" autocomplete="off" checked
                               data-toggle="toggle"
                               data-on="ON"
                               data-off="OFF" />
                    </label>
                </div>

                <div class="row no_padding flex_container">
                    <div class="flex_left">
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="1"><strong><sup>1</sup></strong> You therefore, my child, be strengthened in the grace that is in Christ Jesus.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="2"><strong><sup>2</sup></strong> The things you heard from me among many witnesses, entrust them to faithful people who will be able to teach others also.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="3"><strong><sup>3</sup></strong> Suffer hardship with me, as a good soldier of Christ Jesus.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="4"><strong><sup>4</sup></strong> No soldier serves while entangled in the affairs of this life, so that he may please his superior officer.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="5"><strong><sup>5</sup></strong> Also, if someone competes as an athlete, he is not crowned unless he competes by the rules.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="6"><strong><sup>6</sup></strong> It is necessary that the hardworking farmer receive his share of the crops first.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="7"><strong><sup>7</sup></strong> Think about what I am saying, for the Lord will give you understanding in everything.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="8"><strong><sup>8</sup></strong> Remember Jesus Christ, from David's seed, who was raised from the dead. This is according to my gospel message,</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="9"><strong><sup>9</sup></strong> for which I am suffering to the point of being bound with chains as a criminal. But the word of God is not bound.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="10"><strong><sup>10</sup></strong> Therefore I endure all things for those who are chosen, so that they also may obtain the salvation that is in Christ Jesus, with eternal glory.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="11"><strong><sup>11</sup></strong> This is a trustworthy saying: "If we have died with him, we will also live with him. </p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="12"><strong><sup>12</sup></strong> If we endure, we will also reign with him. If we deny him, he also will deny us. </p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="13"><strong><sup>13</sup></strong> if we are unfaithful, he remains faithful, for he cannot deny himself." </p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="14"><strong><sup>14</sup></strong> Keep reminding them of these things. Warn them before God against quarreling about words; it is of no value, and only ruins those who listen. <span data-toggle="tooltip" data-placement="auto auto" title="" class="booknote mdi mdi-bookmark" data-original-title=" Some versions read, [ Warn them before the Lord]"></span></p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="15"><strong><sup>15</sup></strong> Do your best to present yourself to God as one approved, a worker who has no reason to be ashamed, who accurately teaches the word of truth.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="16"><strong><sup>16</sup></strong> Avoid profane talk, which leads to more and more godlessness.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="17"><strong><sup>17</sup></strong> Their talk will spread like cancer. Among them are Hymenaeus and Philetus,</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="18"><strong><sup>18</sup></strong> who have gone astray from the truth. They say that the resurrection has already happened, and they destroy the faith of some.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="19"><strong><sup>19</sup></strong> However, the firm foundation of God stands. It has this inscription: "The Lord knows those who are his" and "Everyone who names the name of the Lord must depart from unrighteousness."</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="20"><strong><sup>20</sup></strong> In a wealthy home, there are not only containers of gold and silver. There are also containers of wood and clay. Some of these are for honorable use, and some for dishonorable.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="21"><strong><sup>21</sup></strong> If someone cleans himself from dishonorable use, he is an honorable container. He is set apart, useful to the Master, and prepared for every good work.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="22"><strong><sup>22</sup></strong> Flee youthful lusts. Pursue righteousness, faith, love, and peace with those who call on the Lord out of a clean heart.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="23"><strong><sup>23</sup></strong> But refuse foolish and ignorant questions. You know that they give birth to arguments.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="24"><strong><sup>24</sup></strong> The Lord's servant must not quarrel. Instead he must be gentle toward all, able to teach, and patient.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="25"><strong><sup>25</sup></strong> He must in meekness educate those who oppose him. God may perhaps give them repentance for the knowledge of the truth.</p>
                        <p style="margin: 0 0 10px;" class="verse_p" data-verse="26"><strong><sup>26</sup></strong> They may become sober again and leave the devil's trap, after they have been captured by him for his will.</p>
                    </div>
                    <div class="flex_middle input_draft">
                        <div class="input_editor textarea"
                             data-totalmarkers="26"></div>
                    </div>
                </div>
            </div>

            <div class="main_content_footer row">
                <div class="form-group">
                    <div class="main_content_confirm_desc"><?php echo __("confirm_finished")?></div>
                    <label><input name="confirm_step" id="confirm_step" value="1" type="checkbox"> <?php echo __("confirm_yes")?></label>
                </div>

                <button id="next_step" class="btn btn-primary" disabled="disabled">
                    <?php echo __($data["next_step"])?>
                </button>
                <img src="<?php echo template_url("img/saving.gif") ?>" class="unsaved_alert">
            </div>
        </form>
        <div class="step_right alt"><?php echo __("step_num", ["step_number" => 1])?></div>
    </div>
</div>

<div class="content_help closed">
    <div id="help_hide" class="glyphicon glyphicon-chevron-left"> <?php echo __("help") ?></div>

    <div class="help_float">
        <div class="help_info_steps">
            <div class="help_name_steps"><span><?php echo __("step_num", ["step_number" => 1])?>:</span> <?php echo __("multi-draft_input_mode")?></div>
            <div class="help_descr_steps">
                <ul>
                    <li><b><?php echo __("purpose") ?></b> <?php echo __("multi-draft_input_mode_purpose") ?></li>
                    <li><?php echo __("verse_markers_help_1") ?></li>
                    <li><?php echo __("verse_markers_help_2") ?></li>
                    <li><?php echo __("verse_markers_help_3") ?></li>
                    <li><?php echo __("verse_markers_help_4") ?></li>
                    <li><?php echo __("verse_markers_help_5", ["step" => __($data["next_step"])]) ?></li>
                </ul>
                <div class="show_tutorial_popup"> >>> <?php echo __("show_more")?></div>
            </div>
        </div>

        <div class="event_info">
            <div class="participant_info">
                <div class="additional_info">
                    <a href="/events/demo<?php echo $addUri ?>/information"><?php echo __("event_info") ?></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="tutorial_container">
    <div class="tutorial_popup">
        <div class="tutorial-close glyphicon glyphicon-remove"></div>
        <div class="tutorial_pic">
            <img src="<?php echo template_url("img/steps/icons/content-review.png") ?>" height="100px" width="100px">
            <img src="<?php echo template_url("img/steps/big/content-review.png") ?>" height="280px" width="280px">
        </div>

        <div class="tutorial_content">
            <h3><?php echo __("multi-draft_input_mode")?></h3>
            <ul>
                <li><b><?php echo __("purpose") ?></b> <?php echo __("multi-draft_input_mode_purpose") ?></li>
                <li><?php echo __("verse_markers_help_1") ?></li>
                <li><?php echo __("verse_markers_help_2") ?></li>
                <li><?php echo __("verse_markers_help_3") ?></li>
                <li><?php echo __("verse_markers_help_4") ?></li>
                <li><?php echo __("verse_markers_help_5", ["step" => __($data["next_step"])]) ?></li>
            </ul>
        </div>
    </div>
</div>

<script src="<?php echo template_url("js/markers.js?4") ?>"></script>
<link rel="stylesheet" href="<?php echo template_url("css/markers.css?3") ?>">

<script>
    $(document).ready(function () {
        $("#next_step").click(function (e) {
            e.preventDefault();
            if(!hasChangesOnPage) window.location.href = '/events/demo-'+inputMode+'/self_check';
            return false;
        });
    });

    $(".input_editor").markers({
        movableButton: true
    });

    $(".source_mode input").change(function () {
        const active = $(this).prop('checked');
        if (active) {
            $(".flex_left").show();
            $(".flex_middle").css("min-height", "initial");
        } else {
            $(".flex_left").hide();
            $(".flex_middle").css("min-height", 600);
        }
    });
</script>