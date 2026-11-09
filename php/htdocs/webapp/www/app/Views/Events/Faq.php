<h2 style="font-weight: bold;"><?php use Helpers\Constants\Projects;

    echo __("faq_tools") ?></h2>

<div class="faq_filter form-inline" style="width: 700px">
    <div class="form-group">
        <label for="faqfilterpage" class="sr-only"><?php echo __("filter_by_search") ?></label>
        <input type="text" class="form-control" size="60" id="faqfilterpage" placeholder="<?php echo __("filter_by_search") ?>" value="">
    </div>
    <div class="form-group">
        <select class="faq_cat form-control" name="category">
            <option value="" hidden><?php echo __('filter_by_category'); ?></option>
            <option value="common"><?php echo __("common") ?></option>
            <?php foreach (Projects::list() as $project): ?>
            <option value="<?php echo $project ?>"><?php echo __($project) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <button class="reset_faq_filter btn btn-danger"><?php echo __("clear_filter") ?></button>
    </div>
</div>

<div class="faqs_page">
    <?php if(!empty($data["faqs"])): ?>
        <?php foreach($data["faqs"] as $faq): ?>
            <div class="faq" data-category="<?php echo $faq->category ?>">
                <div class="faq_question_header">
                    <span class="glyphicon glyphicon-triangle-right"></span>
                    <?php echo $faq->title ?>
                </div>
                <div class="faq_answer_content">
                    <div class="faq_text"><?php echo preg_replace("/\n/", "<br>", $faq->text) ?></div>
                    <div class="faq_category"><?php echo __($faq->category) ?></div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
    <div><?php echo "No FAQ. <a href='/admin/tools'>Create</a>." ?></div>
    <?php endif; ?>
</div>

<script>
    $(function() {
        $(".faq_question_header").click(function () {
            $(this).next().slideToggle(300);
            $("span", this).toggleClass("glyphicon-triangle-bottom glyphicon-triangle-right");
        });

        $("body").on("keyup", "#faqfilterpage", function () {
            var w = $(this).val();
            var re = new RegExp(w, "ig");
            var cat = $(".faq_cat").val();

            $(".faqs_page").children().hide();
            $(".faqs_page").children().filter(function () {
                return $(this).text().match(re)
                    && (cat != "" ? $(this).data("category") == cat : true);
            }).show();
        });

        $("body").on("change", ".faq_cat", function() {
            var w = $("#faqfilterpage").val();
            var re = new RegExp(w, "ig");
            var cat = $(this).val();

            $(".faqs_page").children().hide();
            $(".faqs_page").children().filter(function () {
                return $(this).text().match(re)
                    && (cat != "" ? $(this).data("category") == cat : true);
            }).show();
        });

        $(".reset_faq_filter").click(function() {
            $("#faqfilterpage").val("");
            $(".faq_cat").val("");
            $(".faqs_page").children().show();
        });
    });
</script>