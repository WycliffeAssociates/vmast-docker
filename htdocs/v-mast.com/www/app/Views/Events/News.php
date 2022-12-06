<div class="news_page">
    <?php if(!empty($data["news"])): ?>
    <ul>
        <?php foreach($data["news"] as $news): ?>
        <li>
            <div class="news_new glyphicon glyphicon-asterisk"
                 style="<?php echo isset($_COOKIE["newsid".$news->id]) ? "display:none" : "" ?>"></div>
            <h3 class="news_title"><?php echo $news->title ?></h3>
            <div class="news_category">
                <?php echo __($news->category) ?> |
                <span class="datetime" data="<?php echo $news->created_at != "" && $news->created_at != "0000-00-00 00:00:00" ?
                    date(DATE_RFC2822, strtotime($news->created_at)) : "" ?>">
                    <?php echo $news->created_at ?>
                </span>
            </div>
            <div class="news_text"><?php echo preg_replace("/\n/", "<br>", $news->text) ?></div>
        </li>
        <?php setcookie("newsid".$news->id, true, time() + 365*60*60, "/") ?>
        <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <div><?php echo "No news" ?></div>
    <?php endif; ?>
</div>