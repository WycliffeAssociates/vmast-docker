<?php
$textDirection = $textDirection ?? "ltr";
?>

<div class="footnote_editor panel panel-default">
    <div class="panel-heading">
        <h1 class="panel-title"><?php echo __("write_footnote_title")?></h1>
        <span class="footnote-editor-close btn btn-success"><?php echo __("save") ?></span>
        <span class="xbtnf glyphicon glyphicon-remove"></span>
    </div>
    <div class="footnote_window">
        <div class="fn_preview"></div>
        <div class="fn_buttons" dir="<?php echo $textDirection ?>">
            <button class="btn btn-default" data-fn="ft" title="footnote text">ft</button>
            <button class="btn btn-default" data-fn="fqa" title="footnote alternate translation">fqa</button>
        </div>
        <div class="fn_builder"></div>
    </div>
</div>

<script>
    $(document).ready(function() {
        let footnoteCallback;

        $(document).on("click", ".editFootNote", function() {
            let footNoteTextarea = $(this).closest(".flex_chunk, .flex_container")
                .find(".peer_verse_ta, .input_mode_ta");

            if(footNoteTextarea.length > 0) {
                let startPosition = footNoteTextarea.prop("selectionStart");
                let endPosition = footNoteTextarea.prop("selectionEnd");
                let text = footNoteTextarea.val();
                let matchedNote = "";
                const matches = text.match(/\\f\s[+-]\s(.*?)\\f\*/gi);
                const ranges = [];

                if(matches != null && matches.length > 0) {
                    for(let i = 0; i < matches.length; i++) {
                        ranges.push([
                            text.indexOf(matches[i]),
                            text.indexOf(matches[i]) + matches[i].length
                        ]);
                    }
                }

                /* define if cursor is in the range of an existent footnote */
                for (let i = 0; i < ranges.length; i++) {
                    if(startPosition >= ranges[i][0] && startPosition <= ranges[i][1]) {
                        startPosition = ranges[i][0];
                        endPosition = ranges[i][1];
                        matchedNote = matches[i];
                    }
                }

                openFootnoteEditor(startPosition, endPosition, matchedNote, $(this).offset().top - 80);

                footnoteCallback = function(footnote) {
                    if(footnote.trim() === "\\f + \\f*")
                        footnote = "";

                    text = text.substring(0, startPosition)
                        + footnote
                        + text.substring(endPosition, text.length);
                    footNoteTextarea.val(text);
                    footNoteTextarea.keyup();
                    $(".footnote_editor").hide();
                }
            }
        });

        $(".xbtnf").click(function () {
            $(".footnote_editor").hide();
        });

        function openFootnoteEditor(startPos, endPos, footnote, offset) {
            $(".footnote_editor").hide();
            $(".footnote_editor .fn_builder").html("");
            $(".footnote_editor .fn_preview").text("");

            const footnoteHtml = parseFootnote(footnote);
            $(".footnote_editor .fn_builder").html(footnoteHtml);
            renderFootnotesPreview();

            $(".footnote_editor").css("top", offset).show();
        }

        $(document).on("DOMSubtreeModified", ".fn_builder", function () {
            renderFootnotesPreview();
        });

        $(document).on("keyup", ".fn_builder", function () {
            renderFootnotesPreview();
        });

        $(".footnote-editor-close").click(function () {
            const footnote = $(".footnote_editor .fn_preview").text();
            if(footnoteCallback) {
                footnoteCallback(footnote);
                footnoteCallback = null;
            }
        });

        function renderFootnotesPreview() {
            let footnote = "\\f + ";

            $(".footnote_editor .fn_builder input").each(function() {
                if($(this).val().trim() !== "")
                    footnote += "\\" + $(this).data("fn") + " " + $(this).val() + " ";
            });

            footnote += "\\f*";

            $(".footnote_editor .fn_preview").text(footnote);
        }

        function parseFootnote(footnote) {
            if(footnote === "") {
                return "";
            }

            const tags = ["fr", "ft", "fq", "fqa", "fk", "fl"];
            let html = "";

            footnote = footnote
                .replace(/\\f\s[+-]\s/gi, "")
                .replace(/\\f\*/gi, "")
                .replace(/\\fqa\*/gi, "");

            const parts = footnote.split(/\\(f(?:r|t|qa|q|k|l))/gi);
            const map = [];
            let prevTag = "";
            if(parts.length > 1) {
                for(let i=0; i < parts.length; i++) {
                    if(i === 0) continue;

                    if(tags.includes(parts[i])) { /* tag */
                        if(prevTag !== parts[i]) {
                            prevTag = parts[i];
                        }
                    } else {
                        map.push([prevTag, parts[i]]); /* content */
                    }
                }
            }

            for(let i=0; i < map.length; i++) {
                html += "<label class='fn_lm'>"
                    + map[i][0]
                    + ": <input data-fn='" + map[i][0] + "' class='form-control' value='" + escapeQuotes(map[i][1].trim()) + "' />"
                    + "<span class='glyphicon glyphicon-remove fn-remove'></span></label>"
                    + "</label>";
            }

            return html;
        }

        $(document).on("click", ".fn_buttons button", function () {
            const tag = $(this).data("fn");

            if(tag === "link") {
                window.open("https://ubsicap.github.io/usfm/notes_basic/fnotes.html");
                return;
            }

            $(".fn_builder").append(
                "<label class='fn_lm'>" + tag + ": <input data-fn='" + tag + "' class='form-control' />" +
                "<span class='glyphicon glyphicon-remove fn-remove'></span></label>"
            );
        });

        $(document).on("click", ".fn-remove", function () {
            $(this).closest("label").remove();
        });
    });
</script>