<?php
$textDirection = $textDirection ?? "ltr";
$fontLanguage = $fontLanguage ?? "en";
$level = $level ?? 1;
?>

<div class="comment_div panel panel-default text_<?php echo $fontLanguage ?>">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("write_note_title")?></h1>
        <span class="editor-close btn btn-success" data-level="<?php echo $level ?>"><?php echo __("save") ?></span>
        <img width="24" src="<?php echo template_url("img/loader.gif") ?>" class="commentEditorLoader">
        <span class="xbtn glyphicon glyphicon-remove"></span>
    </div>
    <textarea dir="<?php echo $textDirection ?>" class="textarea textarea_editor"></textarea>
    <div class="comments_list <?php echo $textDirection ?>"></div>
</div>

<script>
    $(document).ready(function() {
        let commentAutoSaveTimer;
        let commentChanged = false;
        let commentSent = false;

        $(".comment_div .textarea_editor").on("keyup", function() {
            commentChanged = true;
            commentSent = false;
        });

        $(document).on("click", ".editComment", function() {
            const comment_container = $(".comment_div");
            comment_container.hide();

            const comments = $(this).next(".comments");
            const unsavedComment = $(".comment_unsaved", comments);
            const unsavedCommentValue = unsavedComment.length > 0 ? unsavedComment.text() : "";

            const top = $(this).offset().top - 80;
            comment_container.css("top", top).show();

            $("textarea", comment_container).val(unsavedCommentValue).focus();

            lastCommentEditor = $(this);
            $(".comments_list")
                .attr("data-chunk", lastCommentEditor.data("chunk"))
                .html(comments.html());

            clearInterval(commentAutoSaveTimer);
            commentAutoSaveTimer = setInterval(function() {
                if (commentChanged) {
                    saveComment(true);
                }
            }, 3000);
        });

        $(".xbtn").click(function () {
            $(".comment_div").hide();
            clearInterval(commentAutoSaveTimer);
        });

        $(".editor-close").click(function() {
            saveComment(false);
        });

        $(document).on("click", ".comment-delete", function() {
            const $this = $(this);
            const id = $this.parent(".comment").data("id");

            if (id === 0) {
                $(this).parent(".comment").remove();
                return;
            }

            $.ajax({
                url: "/events/rpc/delete_comment",
                method: "post",
                data: { cID: id },
                dataType: "json",
                beforeSend: function() {
                    $(".commentEditorLoader").show();
                }
            })
                .done(function(data) {
                    if(data.success) {
                        const comments = lastCommentEditor.next(".comments");
                        $(".comment[data-id="+id+"]").remove();

                        const num = comments.children().length > 0 ? comments.children().length : "";
                        lastCommentEditor.prev(".comments_number").addClass("hasComment").text(num);
                        if(num <= 0) lastCommentEditor.prev(".comments_number").removeClass("hasComment");

                        if ($(".my_comment", comments).length <= 0) {
                            lastCommentEditor.css("color", "#333333");
                        }

                        const msg = {
                            type: "comment",
                            eventID: eventID,
                            verse: lastCommentEditor.data("chunk"),
                            deleted: true,
                            cID: id
                        };
                        socket.emit("system message", msg);
                    } else {
                        if(typeof data.error != "undefined") {
                            renderPopup(data.error, function () {
                                window.location.reload();
                            });
                        }
                    }
                })
                .error(function () {
                    renderPopup(Language.commonError);
                })
                .always(function() {
                    $(".commentEditorLoader").hide();
                });
        });

        $(".comment_div, .footnote_editor").draggable({
            snap: 'inner',
            handle: '.panel-heading',
            containment: 'document'
        });

        function saveComment(autoSaver) {
            const closeButton = $(".editor-close");
            const comments = lastCommentEditor.next(".comments");
            const comment_container = $(".comment_div");
            const unsavedComment = $(".comment_unsaved", comments);

            const text = $("textarea", comment_container).val().trim();
            const level = closeButton.data("level") || 1;

            if (text === "" && !autoSaver) {
                comment_container.hide();
                return;
            }

            const chapchunk = lastCommentEditor.data("chunk").split(":");

            if(typeof isDemo != "undefined" && isDemo) {
                if (!autoSaver) {
                    comment_container.hide();
                }
                commentChanged = false;
            } else {
                $.ajax({
                    url: "/events/rpc/save_comment",
                    method: "post",
                    data: {
                        eventID: eventID,
                        chapter: chapchunk[0],
                        chunk: chapchunk[1],
                        comment: text,
                        level: level,
                        autoSaver: autoSaver
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $(".commentEditorLoader").show();
                    }
                })
                    .done(function(data) {
                        if(data.success) {
                            if (!autoSaver) {
                                comment_container.hide();
                                $("textarea", comment_container).val("");
                                unsavedComment.text("");

                                data.text = unEscapeStr(data.text);

                                lastCommentEditor.css("color", "#a52f20");
                                const deleteButton = $("<span />").addClass("comment-delete mdi mdi-delete-circle");
                                const levelMarker = $("<span />")
                                    .addClass("mdi mdi-numeric-"+level+"-box-multiple-outline")
                                    .prop("title", "Level " + level);
                                const name = $("<b />").text(data.user + ": ");

                                const comment = $("<div />").addClass("comment my_comment")
                                    .attr("data-id", data.cID)
                                    .append(deleteButton)
                                    .append(levelMarker)
                                    .append(" ")
                                    .append(name)
                                    .append(data.text);
                                comments.prepend(comment.prop("outerHTML"));

                                const num = comments.children(".comment").length > 0 ? comments.children(".comment").length : "";
                                lastCommentEditor.prev(".comments_number").addClass("hasComment").text(num);

                                const msg = {
                                    type: "comment",
                                    eventID: eventID,
                                    verse: lastCommentEditor.data("chunk"),
                                    text: data.text,
                                    level: level,
                                    cID: data.cID
                                };
                                socket.emit("system message", msg);
                            }

                            commentChanged = false;
                            commentSent = true;
                        } else {
                            if(data.error !== undefined) {
                                renderPopup(data.error, function () {
                                    window.location.reload();
                                });
                            }
                        }
                    })
                    .error(function () {
                        renderPopup(Language.commonError);
                    })
                    .always(function() {
                        $(".commentEditorLoader").hide();
                    });
            }
        }
    });
</script>