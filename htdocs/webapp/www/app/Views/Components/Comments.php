<?php

use Helpers\Session;

$hasComments = $hasComments ?? false;
$commentsNumber = $commentsNumber ?? 0;
$commentChunk = $commentChunk ?? "0:0";
$comments = $comments ?? [];
$myMemberID = $myMemberID ?? Session::get("memberID");
$enableFootNotes = $enableFootNotes ?? true;
?>

<div class="notes_tools">
    <div class="comments_number <?php echo $hasComments ? "hasComment" : "" ?>">
        <?php echo $hasComments ? $commentsNumber : ""?>
    </div>

    <span class="editComment mdi mdi-lead-pencil"
          data-chunk="<?php echo $commentChunk ?>"
          title="<?php echo __("write_note_title")?>"></span>

    <div class="comments">
        <?php if($hasComments): ?>
            <?php foreach($comments as $comment): ?>
                <?php if ($comment->saved): ?>
                    <div class="comment <?php echo $comment->memberID == $myMemberID ? "my_comment" : "" ?>" data-id="<?php echo $comment->cID ?>">
                        <?php if ($comment->memberID == $myMemberID): ?>
                            <span class="comment-delete mdi mdi-delete-circle"></span>
                        <?php endif; ?>
                        <span
                            class="mdi mdi-numeric-<?php echo $comment->level ?>-box-multiple-outline"
                            title="<?php echo __("level", $comment->level) ?>"></span>
                        <b><?php echo $comment->firstName." ".mb_substr($comment->lastName, 0, 1) ?>:</b>
                        <?php echo $comment->text ?>
                    </div>
                <?php elseif ($comment->memberID == $myMemberID): ?>
                    <div class="comment_unsaved"><?php echo $comment->text ?></div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if ($enableFootNotes): ?>
    <span class="editFootNote mdi mdi-bookmark" title="<?php echo __("write_footnote_title") ?>"></span>
    <?php endif; ?>
</div>
