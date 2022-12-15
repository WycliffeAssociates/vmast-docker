var currentChunk = -1;
var firstVerse = 0;
var lastVerse = 0;
var chunks = [];
var lastCommentEditor;
var hasChangesOnPage = false;
var changesQueued = false;
var hasInputModeChangesOnPage = false;
var inputModeChangesQueued = false;
var autoSaveTimer;
var autoSaveRequest;

const EventSteps = {
    NONE: "none",
    PRAY: "pray",
    CONSUME: "consume",
    HIGHLIGHT: "highlight",
    VERBALIZE: "verbalize",
    CHUNKING: "chunking",
    READ_CHUNK: "read-chunk",
    BLIND_DRAFT: "blind-draft",
    MULTI_DRAFT: "multi-draft",
    REARRANGE: "rearrange",
    SYMBOL_DRAFT: "symbol-draft",
    THEO_CHECK: "theo-check",
    BT_CHECK: "bt-check",
    SELF_CHECK: "self-check",
    PEER_REVIEW: "peer-review",
    KEYWORD_CHECK: "keyword-check",
    CONTENT_REVIEW: "content-review",
    FINAL_REVIEW: "final-review",
    FINISHED: "finished",
};

const EventCheckSteps = {
    NONE: "none",
    PRAY: "pray",
    CONSUME: "consume",
    FST_CHECK: "fst-check",
    SND_CHECK: "snd-check",
    PEER_REVIEW_L2: "peer-review-l2",
    KEYWORD_CHECK_L2: "keyword-check-l2",
    PEER_REVIEW_L3: "peer-review-l3",
    PEER_EDIT_L3: "peer-edit-l3",
    FINISHED: "finished",
};

const EventMembers = {
    TRANSLATOR: "translator",
    L2_CHECKER: "checker_l2",
    L3_CHECKER: "checker_l3",
};

const NotificationType = {
    STARTED: "started",
    READY: "ready",
    DONE: "done",
};

$(document).ready(function() {

    Offline.options = {
        checks: {
            xhr: {
                url: "/events/rpc/check_internet"
            }
        }
    }

    Offline.on("confirmed-up", function () {
        //console.log("internet is up");
    });

    Offline.on("confirmed-down", function () {
        console.log("internet is down");
    });

    $(window).bind('beforeunload', function(){
        localStorage.setItem("prev", window.location.href);
    });

    $("a, button").click(function (e) {
        if(Offline.state == "up")
            return true;

        renderPopup(Language.connectionLostMessage);
        return false;
    });

    setTimeout(function () {
        $('[data-toggle="tooltip"]').tooltip();
    }, 2000);

    $('.textarea').autosize();

    animateIntro();

    $.widget( "custom.iconselectmenu", $.ui.selectmenu, {
        _renderItem: function( ul, item ) {
            var li = $( "<li>" ),
                wrapper = $( "<div>", { text: item.label } );

            if ( item.disabled ) {
                li.addClass( "ui-state-disabled" );
            }

            $( "<span>", {
                style: item.element.attr( "data-style" ),
                "class": "ui-icon " + item.element.attr( "data-class" )
            })
                .appendTo( wrapper );

            return li.append( wrapper ).appendTo( ul );
        }
    });

    $( "#lang-select" )
        .iconselectmenu({
            width: 60,
            create: function( event, ui ) {
                var lang = getCookie("lang");
                lang = typeof lang != "undefined" ? lang : "en";
                $("#lang-select-button .ui-selectmenu-text").html(
                    "<img src='/app/templates/default/img/" + lang + ".png' width='16' height='12'>"
                );
            },
            select: function( event, ui ) {
                $("#lang-select-button .ui-selectmenu-text").html(
                    "<img src='/app/templates/default/img/" + ui.item.value + ".png' width='16' height='12'>"
                );
                window.location.href = "/lang/" + ui.item.value;
            }
        })
        .iconselectmenu( "menuWidget" )
        .addClass( "ui-menu-icons customicons" );

    // Statement of faith block
    $("#sof").click(function() {
        $('#sof_modal').modal('show');
        return false;
    });

    $("#sof_agree").click(function() {
        $("#sof").prop('checked', true);
        $('#sof_modal').modal('hide');
        $("#sof").closest("label").popover('destroy');
    });

    $("#sof_cancel").click(function() {
        $("#sof").prop('checked', false);
        $('#sof_modal').modal('hide');
    });

    // Terms of use block
    $("#tou").click(function() {
        $('#tou_modal').modal('show');
        return false;
    });

    $("#tou_agree").click(function() {
        $("#tou").prop('checked', true);
        $("#tou").closest("label").popover('destroy');
        $('#tou_modal').modal('hide');
    });

    $("#tou_cancel").click(function() {
        $("#tou").prop('checked', false);
        $('#tou_modal').modal('hide');
    });

    // Old/New Testament Tabs
    $("a[href=#new_test]").click(function(e) {
        $("#old_test").hide();
        $("a[href=#old_test]").parent().removeClass("active");

        $("#new_test").show();
        $(this).parent().addClass("active");
        setCookie("testTab", "new_test");
        return false;
    });

    $("a[href=#old_test]").click(function() {
        $("#old_test").show();
        $(this).parent().addClass("active");

        $("a[href=#new_test]").parent().removeClass("active");
        $("#new_test").hide();
        setCookie("testTab", "old_test");
        return false;
    });

    var testTab = getCookie("testTab");
    if(typeof testTab != "undefined")
    {
        $("a[href=#"+testTab+"]").click();
    }

    $(".main_menu .glyphicon-menu-hamburger").click(function(e) {
        const parent = $(this).parent();
        if ($("ul", parent).is(":visible"))
            $("ul", parent).hide();
        else
            $("ul", parent).show();

        e.stopPropagation();
    });

    $(document).click(function(e) {
        if(!e.target.classList.contains("option_group"))
            $(".main_menu ul").hide();
    });

    // Apply for event as translator/checker
    // Start event
    $(".applyEvent").click(function(e) {
        if($(this).hasClass("eventFull"))
        {
            renderPopup(Language.eventClosed);
            return false;
        }

        var eventID = $(this).attr("data");
        var bookName = $(this).attr("data2");
        var stage = $(this).attr("data3");

        $("#applyEvent").trigger("reset");
        $(".errors").html("");
        $("label").removeClass("label_error");
        $(".bookName").text(bookName);
        $("#eventID").val(eventID);

        if(stage == "d1")
        {
            $(".checker_info").hide();
            $(".ftr").show();
            $(".fl2, .fl3").hide();
            $("input[name=userType]").val("translator");

            renderConfirmPopup(Language.applyForEventConfirmTitle, Language.applyForEventConfirm, function () {
                $(this).dialog("close");
                $("#applyEvent").submit();
            }, function () {
                $(this).dialog("close");
            });
        }
        else
        {
            $(".panel-title.applyForm").text(bookName);
            if(stage == "l2")
            {
                $(".ftr, .fl3").hide();
                $(".fl2").show();
                $("input[name=userType]").val("checker_l2");
            }
            else
            {
                $(".ftr, .fl2").hide();
                $(".fl3").show();
                $("input[name=userType]").val("checker_l3");
            }
            $(".checker_info").show();
            $(".event-content").css("left", 0);
        }

        e.preventDefault();
    });

    // Submit apply event form
    $("#applyEvent").submit(function(e) {

        $.ajax({
            url: $("#applyEvent").prop("action"),
            method: "post",
            data: $("#applyEvent").serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".applyEventLoader").show();
            }
        })
            .done(function(data) {
                $("label").removeClass("label_error");

                if(data.success)
                {
                    $(".panel-close").click();
                    renderPopup(data.success, function () {
                        window.location = "/events";
                    });
                }
                else
                {
                    renderPopup(data.error);
                    //$(".errors").html(data.error);

                    if(typeof data.errors != "undefined")
                    {
                        $.each(data.errors, function(k, v) {
                            $("label."+k).addClass("label_error");
                        });
                    }
                }
            })
            .always(function() {
                $(".applyEventLoader").hide();
            });

        e.preventDefault();
    });

    $(".panel-close").click(function() {
        $(this).closest(".form-panel").css("left", -9999);
    });


    // ------------------- Translation Flow ---------------------- //

    // Hide steps panel on small screens or if it was closed manually
    var panelClosed = getCookie("close_left_panel");

    if(typeof panelClosed != "undefined")
    {
        if(panelClosed == "true")
        {
            $("#translator_steps").removeClass("open")
                .addClass("closed");
            $("#translator_steps").animate({left: "-370px"}, 50, function() {
                $("#tr_steps_hide").removeClass("glyphicon-chevron-left")
                    .addClass("glyphicon-chevron-right");
            });
        }
    }
    else if($(window).width() < 1800)
    {
        $("#translator_steps").removeClass("open")
            .addClass("closed");
        $("#translator_steps").animate({left: "-370px"}, 500, function() {
            $("#tr_steps_hide").removeClass("glyphicon-chevron-left")
                .addClass("glyphicon-chevron-right");
        });
    }

    $(document).on("keyup paste change", ".peer_verse_ta, .blind_ta, .verse_ta", function() {
        if (hasChangesOnPage) {
            changesQueued = true;
        }
        hasChangesOnPage = true;
        $(".unsaved_alert").show();
    });

    $(".verse_ta:first").focus();

    $(".my_comment").each(function() {
        var pencil = $(this).parent(".comments").prev(".editComment");
        if(pencil.length > 0)
        {
            pencil.css("color", "#a52f20");
        }
    });

    if(typeof step != "undefined") {
        if(step == EventSteps.BLIND_DRAFT || step == EventSteps.SELF_CHECK || step == EventSteps.FINAL_REVIEW ||
            step == EventSteps.PEER_REVIEW || step == EventSteps.KEYWORD_CHECK ||
            step == EventSteps.CONTENT_REVIEW || step == EventSteps.MULTI_DRAFT ||
            step == EventSteps.SYMBOL_DRAFT || step == EventSteps.REARRANGE || step == EventSteps.THEO_CHECK || // For SUN
            step == EventCheckSteps.FST_CHECK || step == EventCheckSteps.SND_CHECK || // For Revision
            step == EventCheckSteps.PEER_REVIEW_L2 ||
            step == EventCheckSteps.PEER_EDIT_L3) {

            autoSaveTimer = setInterval(function() {
                if(typeof isDemo != "undefined" && isDemo) {
                    hasChangesOnPage = false;
                    $(".unsaved_alert").hide();
                }

                if(hasChangesOnPage) {
                    let postData = $("#main_form").serializeArray();
                    postData.push({"name":"eventID","value":eventID});

                    autoSaveRequest = $.ajax({
                        url: "/events/rpc/autosave_chunk",
                        method: "post",
                        data: postData,
                        dataType: "json",
                        beforeSend: function() {

                        }
                    })
                        .done(function(data) {
                            if(data.success) {
                                $(".unsaved_alert").hide();
                                if (!changesQueued) {
                                    hasChangesOnPage = false;
                                } else {
                                    changesQueued = false;
                                }
                            } else {
                                if(typeof data.errorType != "undefined") {
                                    switch (data.errorType) {
                                        case "logout":
                                            window.location.href = "/members/login";
                                            break;

                                        case "verify":
                                            window.location.href = "/";
                                            break;

                                        case "noChange":
                                            $(".unsaved_alert").hide();
                                            hasChangesOnPage = false;
                                            changesQueued = false;
                                            break;

                                        case "checkDone":
                                            $(".unsaved_alert").hide();
                                            hasChangesOnPage = false;
                                            changesQueued = false;
                                            renderPopup(data.error);
                                            break;

                                        case "json":
                                            renderPopup(data.error);
                                            break;
                                    }
                                }
                                console.log(data.error);
                            }
                        })
                        .error(function (xhr, status, error) {
                            debug(status);
                            debug(error);
                            //hasChangesOnPage = false;
                        })
                        .always(function() {

                        });
                }
            }, 3000);
        }
    }

    // Update information page periodically
    if(typeof isInfoPage != "undefined") {
        const projects = ["tn","tq","tw","sun","rad","obs","bc","bca"];
        const infoUpdateTimer = setInterval(function() {
            let tm = typeof tMode != "undefined" && $.inArray(tMode, projects) > -1
                ? "-" + tMode
                : "";

            if(typeof isOdb != "undefined") tm = "-odb" + tm;
            const mm = typeof manageMode != "undefined" ? (manageMode !== "l2" ? "-"+manageMode : "-revision") : "";

            $.ajax({
                url: "/events/information" + tm + mm + "/" + eventID,
                method: "get",
                dataType: "json",
            })
                .done(function(data) {
                    if(data.success)
                    {
                        // Update overall progress value
                        if(data.progress > 0) {
                            $(".progress_all").removeClass("zero");
                            $(".progress_all .progress-bar").css('width', Math.floor(data.progress)+"%")
                                .attr('aria-valuenow', Math.floor(data.progress))
                                .text(Math.floor(data.progress)+"%");
                        }

                        // Update members list
                        $.each(data.members, function (memberID, member) {
                            if(isNaN(parseInt(memberID))) return true;

                            memberID = parseInt(memberID);

                            if($(".members_list .member_item[data="+memberID+"]").length <= 0)
                            {
                                var memberItem = $("<div></div>").appendTo(".members_list")
                                    .addClass("member_item")
                                    .attr("data", memberID);

                                memberItem.append('<span class="online_indicator glyphicon glyphicon-record">&nbsp;</span>');
                                memberItem.append('<span class="member_uname">'+member.name+'</span> ');
                                memberItem.append('<span class="member_admin">'+(data.admins.indexOf(memberID) > -1 ? "("+Language.facilitator+") " : "")+'</span>');
                                memberItem.append('<span class="online_status">'+Language.statusOnline+'</span>');
                                memberItem.append('<span class="offline_status">'+Language.statusOffline+'</span>');
                            }
                        });

                        // Update chapters list
                        var openedItems = [];
                        $.each($(".section_header"), function () {
                            var isCollapsed = $(".section_arrow", $(this)).hasClass("glyphicon-triangle-right");
                            if(!isCollapsed)
                                openedItems.push($(this).attr("data"));
                        });

                        $(".chapter_list").html(data.html);

                        $.each(openedItems, function (i,v) {
                            var section = $(".section_header[data="+v+"]");
                            var content = section.next(".section_content");
                            content.show(0);
                            $(".section_arrow", section)
                                .removeClass("glyphicon-triangle-right")
                                .addClass("glyphicon-triangle-bottom");
                            $(".section_title", section).css("font-weight", "bold");

                        });
                        openedItems = [];
                        localizeDate();
                    }
                    else
                    {
                        switch (data.errorType)
                        {
                            case "logout":
                                window.location.href = "/members/login";
                                break;

                            case "not_started":
                            case "empty_no_permission":
                                window.location.href = "/";
                                break;
                        }
                    }
                })
                .always(function() {

                });
        }, 60000);
    }

    var mouseTimeout;
    $("body").on("mouseover", ".more_chunks", function (e) {
        e.stopPropagation();

        var blind = $(this).find(".chunks_blind");
        var parent = $(this);

        if(blind.length <= 0) return false;

        if(typeof $(this).data("e") != "undefined"
            && $(this).data("e").currentTarget == e.currentTarget)
            clearTimeout(mouseTimeout);
        parent.css("height", 140).css("overflow-y", "auto");
        blind.css("background-image", "none")
            .removeClass("glyphicon-triangle-bottom");

        $(this).removeData("e");
    });

    $("body").on("mouseout", ".more_chunks", function (e) {
        e.stopPropagation();

        var blind = $(this).find(".chunks_blind");
        var parent = $(this);

        if(blind.length <= 0) return false;
        $(this).data("e", e);

        mouseTimeout = setTimeout(function () {
            parent.css("height", 70).css("overflow", "hidden");
            parent[0].scrollTop = 0;
            blind.css("background-image", "linear-gradient(to bottom, rgba(255, 255, 255, 0.3) 0px, rgba(255, 255, 255, 0.9) 100%)")
                .addClass("glyphicon-triangle-bottom");
        }, 100);
    });

    // Show/Hide Steps Panel
    $("#tr_steps_hide").click(function () {
        if($("#translator_steps").hasClass("open"))
        {
            $("#translator_steps").removeClass("open")
                .addClass("closed");
            $("#translator_steps").animate({left: "-370px"}, 500, function() {
                $("#tr_steps_hide").removeClass("glyphicon-chevron-left")
                    .addClass("glyphicon-chevron-right");
            });
            setCookie("close_left_panel", true, {expires: 365*24*60*60, path: "/"});
        }
        else
        {
            $("#translator_steps").removeClass("closed")
                .addClass("open");
            $("#translator_steps").animate({left: 0}, 500, function() {
                $("#tr_steps_hide").removeClass("glyphicon-chevron-right")
                    .addClass("glyphicon-chevron-left");
                setCookie("close_left_panel", false, {expires: 365*24*60*60, path: "/"});
            });
        }
    });

    $("form #confirm_step").prop("checked", false);
    $("form #next_step").prop("disabled", true);

    // Confirm to go to the next step
    $("#confirm_step").change(function() {
        const hasCheckerReady = $("#checker_ready").length > 0;
        const checked = $(this).is(":checked");
        const element = hasCheckerReady ? $("#checker_ready") : $("#next_step");

        if(checked) {
            element.prop("disabled", false);
            $("#next_chapter").prop("disabled", false);
        } else {
            element.prop("disabled", true);
            $("#next_chapter").prop("disabled", true);
        }
    });

    $("#next_step").click(function(e) {
        const $this = $(this);

        if(hasChangesOnPage || hasInputModeChangesOnPage) {
            renderPopup(Language.saveChangesConfirm, function () {
                $(this).dialog("close");
            }, function () {
                $( this ).dialog("close");
            });

            e.preventDefault();
            return false;
        }

        const sending = $this.data("sending");
        if(sending) {
            setTimeout(function() {$this.data("sending", false);}, 5000)
            e.preventDefault();
        } else {
            $this.data("sending", true);
            return true;
        }
    });

    $("#next_step.checker").click(function (e) {
        renderConfirmPopup(Language.checkerConfirmTitle, Language.checkerConfirm,
            function () {
                $("#checker_submit").submit();
                $( this ).dialog("close");
            },
            function () {
                $("#confirm_step").prop("checked", false);
                $("#next_step").prop("disabled", true);
                $("#checker_ready").text(Language.ready_to_check);
                $( this ).dialog("close");
            },
            function () {
                $("#confirm_step").prop("checked", false);
                $("#next_step").prop("disabled", true);
                $("#checker_ready").text(Language.ready_to_check);
                $( this ).dialog("close");
            });

        e.preventDefault();
    });

    $("#checker_submit").submit(function(e) {
        e.preventDefault();

        // Used for Level 3 check
        const checkerStep = $("input[name=step]");

        if(window.opener != null) {
            window.opener.$(".check1 .event_link a[data="+eventID+"_"+chkMemberID+"]")
                .closest(".event_block").remove();
        }

        $.ajax({
            method: "post",
            data: $("#checker_submit").serialize(),
            dataType: "json",
            beforeSend: function() {
                if($(".checkerLoader").length <= 0) {
                    $(".ui-dialog-buttonset")
                        .prepend('<img src="/templates/default/assets/img/loader.gif" style="margin-right:10px" width="32" class="checkerLoader">');
                }
                $(".checkerLoader").show();
                $(".ui-dialog-buttonset button").prop("disabled", true);
            }
        })
            .done(function(data) {
                if(data.success) {
                    const data = {
                        type: "checkDone",
                        eventID: eventID,
                        chkMemberID: chkMemberID,
                    };
                    socket.emit("system message", data);

                    const to = $(".event_data_to").val();
                    const step = $(".event_data_step").val();
                    const chapter = $(".event_data_chapter").val();
                    const manageMode = $(".event_data_manage").val() || null;

                    const loader = $(".ready_loader");

                    createNotification(eventID, to, step, chapter, manageMode, NotificationType.DONE, loader)
                        .done(function(data) {
                            if (data.success) {
                                const data = {
                                    type: "notification",
                                    eventID: eventID,
                                    toMemberID: to
                                };
                                socket.emit("system message", data);
                                if(window.opener != null) {
                                    if(checkerStep.length > 0 && checkerStep.val() === EventCheckSteps.PEER_REVIEW_L3) {
                                        window.location.reload();
                                    } else {
                                        window.opener.$(".check1 .event_link a[data="+eventID+"_"+chkMemberID+"]").parent(".event_block").remove();
                                        window.close();
                                    }
                                } else {
                                    window.location = "/events";
                                }
                            } else {
                                renderPopup(data.error);
                            }
                        });
                } else {
                    let message = "";
                    $.each(data.errors, function(i, v) {
                        message += v + "<br>";
                    });

                    if (typeof data.kw_exist != "undefined") {
                        renderConfirmPopup(Language.skip_keywords, Language.skip_keywords_message,
                            function () {
                                $("input[name=skip_kw]").val(1);
                                $("input[name=confirm_step]").prop("checked", true);
                                $("#checker_submit").submit();
                                $( this ).dialog("close");
                            },
                            function () {
                                $("#confirm_step").prop("checked", false);
                                $("#next_step").prop("disabled", true);
                                $( this ).dialog("close");
                            },
                            function () {
                                $("#confirm_step").prop("checked", false);
                                $("#next_step").prop("disabled", true);
                                $( this ).dialog("close");
                            })
                    } else {
                        renderPopup(message);
                    }
                    console.log(data);
                }
            })
            .always(function() {
                $(".checkerLoader").hide();
                $(".ui-dialog-buttonset button").prop("disabled", false);
            });

        return false;
    });

    // Add verse to chunk
    $(document).on("click", ".verse_number", function(e) {
        var p = $(this).parent().parent();
        var createChunkBtn = $(".clone.create_chunk").clone();
        var resetBtn = $(".clone.chunks_reset").clone();

        createChunkBtn.removeClass("clone");
        resetBtn.removeClass("clone");

        var verses = parseCombinedVerses($(this).val());

        if($(this).is(":checked")) // Select verse
        {
            for(var i=0; i<verses.length; i++)
            {
                var verse = verses[i];

                // Do not check if there is no checked verses and one checks any verse after first
                // Do not check if one skips verse(s)
                if((verse > 1 && chunks.length <= 0) ||
                    verse > (lastVerse + 1))
                {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }

                if(currentChunk < 0) // Create first chunk
                {
                    chunks[0] = [];
                    chunks[0].push(verse);
                    currentChunk = 0;
                    firstVerse = verse;

                    //$(".chunks_reset").show();
                }
                else
                {
                    if(typeof chunks[currentChunk] == "undefined")
                    {
                        chunks[currentChunk] = [];
                        firstVerse = verse;
                    }
                    chunks[currentChunk].push(verse);
                }

                lastVerse = verse;
            }
            $(".verse_p .create_chunk").remove();
            $(".verse_p .chunks_reset").remove();

            p.append(createChunkBtn);
            p.append(resetBtn);
        }
        else                     // Deselect verse from the end
        {
            var prep_p = p.prev(".verse_p");

            for(var i=verses.length - 1; i>=0; i--)
            {
                var verse = verses[i];

                if(verse != lastVerse)
                {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }

                if(typeof chunks[currentChunk] == "undefined")
                {
                    $(".chunk_divider:last").remove();
                    if(typeof chunks[currentChunk-1] != "undefined")
                        firstVerse = chunks[currentChunk-1][0];
                    currentChunk--;
                }

                var chunk = typeof chunks[currentChunk] != "undefined" ? currentChunk : currentChunk-1;
                var vIndex = chunks[chunk].indexOf(verse);
                chunks[chunk].splice(vIndex, 1);

                lastVerse = verse-1;

                if(chunks[chunk].length <= 0)
                {
                    chunks.splice(chunk, 1);
                }

                if(chunks.length <= 0)
                {
                    firstVerse = 0;
                    //$(".chunks_reset").hide();
                }
            }

            $(".verse_p .create_chunk").remove();
            $(".verse_p .chunks_reset").remove();

            if(prep_p.length > 0)
            {
                prep_p.append(createChunkBtn);
                prep_p.append(resetBtn);
            }
        }

        $("#chunks_array").val(JSON.stringify(chunks));

        fv = firstVerse < 10 ? "0"+firstVerse : firstVerse;
        lv = lastVerse < 10 ? "0"+lastVerse : lastVerse;
        $(".verse_p .create_chunk").text(fv+"-"+lv).attr("title", Language.makeChunk+" "+fv+"-"+lv).show();
        $(".verse_p .chunks_reset").show();
    });

    // Start new chunk
    $(document).on("click", ".verse_p .create_chunk", function() {
        currentChunk++;
        $(".verse_p .create_chunk").parent().after('<div class="chunk_divider col-sm-12"></div>');
        $(".verse_p .create_chunk").remove();
        $(".verse_p .chunks_reset").css("top", -15);
    });

    // Reset chunks
    $(document).on("click", ".verse_p .chunks_reset", function() {
        chunks = [];
        currentChunk = -1;
        firstVerse = 0;
        lastVerse = 0;

        $(this).remove();
        $(".verse_p .create_chunk").remove();
        $(".chunk_divider").remove();
        $(".verse_number").prop("checked", false);
        $("#chunks_array").val("[]");
    });

    // Switching translation tabs on peer review step
    $("a[href=#cotr_tab]").click(function() {
        $(".tr_main_content").hide();
        $("a[href=#tr_tab]").parent().removeClass("active");

        $(".cotr_main_content").show();
        $(this).parent().addClass("active");
        return false;
    });

    $("a[href=#tr_tab]").click(function() {
        $(".tr_main_content").show();
        $(this).parent().addClass("active");

        $("a[href=#cotr_tab]").parent().removeClass("active");
        $(".cotr_main_content").hide();

        $(".verse").each(function() {
            const verseTa = $(".verse_ta, .peer_verse_ta", $(this).parent());
            const height = $(this).height() / verseTa.length;
            verseTa.css("min-height", height);
        });
        return false;
    });

    // Toggle Side by Side view on content review page
    $("#side_by_side_toggle").click(function() {
        if($(this).is(":checked"))
        {
            $(".side_by_side_translator .chunk_verses.flex_left").show();
            $(".side_by_side_checker .flex_middle.editor_area").show();
        }
        else
        {
            $(".side_by_side_translator .chunk_verses.flex_left").hide()
            $(".side_by_side_checker .flex_middle.editor_area").hide();
        }
    });

    // Show/Hide notifications
    $(".notifications").click(function() {
        if(!$(".notif_block").is(":visible"))
        {
            $(".notif_block").show();
            return false;
        }
        else
        {
            $(".notif_block").hide();
        }
    });

    $(document).click(function() {
        $(".notif_block").hide();
    });

    $(document).on("click", ".notifa", function(e) {
        e.preventDefault();
        const $this = $(this);
        const type = $this.data("type");
        const url = $this.attr("href");

        if (type === NotificationType.STARTED) {
            renderConfirmPopup(Language.checkBookConfirmTitle, Language.checkBookConfirm, function () {
                $this.remove();
                decrementNotifications();

                $( this ).dialog( "close" );
                window.open(url);
            }, function () {
                $( this ).dialog( "close" );
            });
        } else {
            $this.remove();
            decrementNotifications();
            window.open(url);
        }
    });

    $("#checker_ready").click(function(e) {
        e.preventDefault();

        let $this = $(this);

        if (typeof isDemo != 'undefined' && isDemo) {
            $this.text(Language.sent).prop("disabled", true);
            $("#next_step").prop("disabled", false);
            return;
        }

        const to = $(".event_data_to").val();
        const step = $(".event_data_step").val();
        const chapter = $(".event_data_chapter").val();
        const manageMode = $(".event_data_manage").val() || null;

        const loader = $(".ready_loader");

        createNotification(eventID, to, step, chapter, manageMode, NotificationType.READY, loader)
            .done(function(data) {
                if (data.success) {
                    $this.text(Language.sent).prop("disabled", true);
                    $("#next_step").prop("disabled", false);
                    const data = {
                        type: "notification",
                        eventID: eventID,
                        toMemberID: to
                    };
                    socket.emit("system message", data);
                } else {
                    renderPopup(data.error);
                }
            });
    });

    $(".check1 .event_link a, .check3 .event_link a").click(function (e) {
        window.open($(this).attr("href"));
        e.preventDefault();
    });

    // Show/Hide Tutorial popup
    $(".tutorial-close").click(function() {
        $(".tutorial_container").hide();
        $("body").css("overflow", "auto");
    });

    $(".show_tutorial_popup").click(function() {
        $(".tutorial_container").show();
        $("body").css("overflow", "hidden");
    });

    // Save keywords
    var isHighlighting = false; // Fix for mobile devices
    $("body").on("mouseup touchend", "div[class^=kwverse]", function (e) {
        if(!isChecker && ["tn"].indexOf(tMode) === -1) return;
        if(typeof disableHighlight != "undefined") return;
        if(typeof isInfoPage != "undefined") return;
        if(typeof step == "undefined"
            || (step != EventSteps.KEYWORD_CHECK
                && step != EventSteps.THEO_CHECK
                && step != EventSteps.HIGHLIGHT)) return;

        var verseID = $(this).attr("class");
        var text;

        var sel;
        if (window.getSelection) {
            sel = window.getSelection();
            if(typeof sel != "undefined")
            {
                if(!sel.isCollapsed)    // Skip caret insert (zero characters select)
                {
                    //sel.modify("extend", "forward", "word");
                    //sel.modify("extend", "backward", "word");
                    var range = sel.getRangeAt(0);

                    // Exclude previous tags from select
                    if(sel.anchorNode.parentNode.nodeName == "SUP" || sel.anchorNode.parentNode.className == "chunk_verses")
                        range.setStart(sel.focusNode, 0);
                    else if(sel.focusNode.parentNode.nodeName == "B") // Exclude highlighted words from select
                        range.setEnd(sel.anchorNode, sel.anchorNode.data.length);

                    text = range.toString();

                    // Remove left and right space from select
                    if(text[text.length-1] == " ")
                        range.setEnd(range.endContainer, range.endOffset-1);
                    if(text[0] == " ")
                        range.setStart(range.startContainer, range.startOffset+1);

                    sel.removeAllRanges();
                    sel.addRange(range);

                    // Allow selection within one verse only
                    if(/^kwverse/.test(range.commonAncestorContainer.parentNode.className))
                    {
                        text = range.toString();

                        // Allow non-empty text and text that doesn't contain reserved words
                        if(text.length > 0 && !/^(?:b|d|a|t|data|dat|at|ata|ta)$/.test(text))
                        {
                            var wText = sel.anchorNode.textContent;
                            var sibling = sel.anchorNode.previousSibling;
                            while(sibling != null)
                            {
                                wText = sibling.textContent + wText;
                                sibling = sibling.previousSibling;
                            }

                            var diff = wText.length - sel.anchorNode.textContent.length;

                            // Find all occurenses of text in the verse
                            var regex = new RegExp("("+text+")", "gmi");
                            var search = [];
                            while (regex.exec(wText) !== null) {
                                search.push(regex.lastIndex);
                            }

                            if(search.length > 0)
                            {
                                // Find index of that text in the verse
                                var offset = Math.max(sel.focusOffset, sel.anchorOffset);
                                var index = search.indexOf(offset + diff);

                                sel.removeAllRanges();
                                isHighlighting = true;
                                setTimeout(function () {
                                    isHighlighting = false;
                                }, 500);
                                //renderConfirmPopup(Language.saveKeywordTitle, Language.saveKeyword + ' <strong>"'+text.trim()+'"</strong>?', function () {
                                //    $(this).dialog("close");
                                saveOrRemoveKeyword(verseID, text, index, false);
                                //});
                            }
                            else
                            {
                                sel.removeAllRanges();
                            }
                        }
                        else
                        {
                            renderPopup(Language.highlightReservedWarning);
                            sel.removeAllRanges();
                        }
                    }
                    else
                    {
                        sel.removeAllRanges();
                        renderPopup(Language.highlightMultipleVerses);
                    }
                }
            }
        }
    });


    $("body").on("mouseover", ".chunk_verses b", function (e) {
        if(!isChecker && ["tn"].indexOf(tMode) === -1) return;
        if(typeof disableHighlight != "undefined") return;
        if(typeof step == "undefined"
            || (step != EventSteps.KEYWORD_CHECK
                && step != EventSteps.THEO_CHECK
                && step != EventSteps.HIGHLIGHT)) return;

        if($(".remove_kw_tip").length <= 0)
        {
            $("body").append("<div class='remove_kw_tip'>"+Language.delKeywordTip+"</div>");
            $(".remove_kw_tip").css("left", $(this).offset().left + 20)
                .css("top", $(this).offset().top - 30);
        }
    });

    $("body").on("mouseout", ".chunk_verses b", function (e) {
        if(isChecker || ["tn"].indexOf(tMode) > -1)
            $(".remove_kw_tip").remove();
    });

    // Delete keyword
    $("body").on("click", "[class*='kwverse_'] b", function () {
        if(typeof isInfoPage != "undefined") return;
        if(typeof disableHighlight != "undefined") return;
        if(isHighlighting) return;

        if(!window.getSelection().isCollapsed) return;

        const isL2Checker = typeof isLevel2 !== "undefined" && !isChecker;

        if((isChecker || ["tn"].indexOf(tMode) > -1 || isL2Checker)
            && (step == EventSteps.KEYWORD_CHECK
                || step == EventSteps.HIGHLIGHT
                || step == EventSteps.THEO_CHECK
                || step == EventCheckSteps.KEYWORD_CHECK_L2
                || step == EventCheckSteps.PEER_REVIEW_L2)) {
            const $this = $(this);
            const text = $(this).text();

            let titleTxt = Language.delKeywordTitle;
            let confirmTxt = Language.delKeyword;
            if(typeof isLevel2 !== "undefined") {
                titleTxt = Language.delKeywordL2Title;
                confirmTxt = Language.delKeywordL2;
            }

            renderConfirmPopup(titleTxt, confirmTxt + ' <strong>"'+text.trim()+'"</strong>', function () {
                $(this).dialog("close");

                const parent = $this.parent("[class*=kwverse_]");
                const classes = parent.attr("class").split(" ");
                const verseID = classes.find(item => item.startsWith("kwverse_"));
                const index = $this.attr("data");

                if (verseID) {
                    saveOrRemoveKeyword(verseID, text, index, true);
                }
            });
        }
    });

    if(typeof isDemo == "undefined")
        loadKeywordsIntoSourse();

    function loadKeywordsIntoSourse() {
        if(typeof eventID == "undefined") return;
        if(typeof step == "undefined"
            || (step != EventSteps.KEYWORD_CHECK
                && step != EventSteps.CONTENT_REVIEW
                && step != EventSteps.FINAL_REVIEW
                && step != EventSteps.HIGHLIGHT
                && step != EventSteps.PEER_REVIEW
                && step != EventCheckSteps.KEYWORD_CHECK_L2
                && step != EventSteps.THEO_CHECK
                && step != EventCheckSteps.PEER_REVIEW_L2)) return;

        if(step == EventSteps.PEER_REVIEW &&
            typeof disableHighlight == "undefined") return;

        $.ajax({
            url: "/events/rpc/get_keywords",
            method: "post",
            data: {
                eventID: eventID,
                chapter: myChapter
            },
            dataType: "json"
        })
            .done(function(data) {
                if(data.success) {
                    $.each(data.text, function (i,v) {
                        const verseID = "kwverse_" + v.chapter + "_" + v.chunk + "_" + v.verse;
                        highlightKeyword(verseID, unEscapeStr(v.text), v.indexOrder);
                    });
                } else {
                    if(typeof data.error !== "undefined") {
                        renderPopup(data.error, function () {
                            window.location.reload(true);
                        });
                    }
                }
            });
    }

    // Summernote text editor for notes
    $(document).ready(function() {
        $(".add_notes_editor, .add_questions_editor").each(function() {
            $(this).summernote({
                lang: siteLang,
                airMode: false,
                placeholder: Language.notesPlaceholder,
                popover: {
                    link: [
                        ['link', ['linkDialogShow', 'unlink']]
                    ],
                    air: [
                        ['para', ['style', 'ul', 'ol']],
                        ['style', ['bold', 'italic', 'underline']],
                        ['link', ['linkDialogShow', 'unlink']],
                        ['misc', ['undo', 'redo']]
                    ]
                },
                toolbar: [
                    ['para', ['style', 'ul', 'ol']],
                    ['style', ['bold', 'italic', 'underline']],
                    ['link', ['linkDialogShow', 'unlink']],
                    ['misc', ['undo', 'redo', 'codeview']]
                ],
                callbacks: {
                    onInit: function() {
                        var parent = $(this).closest(".note_chunk, .questions_chunk");
                        var noteContent = $(".note_content, .question_content", parent);
                        var height = noteContent.actual("height");
                        $(".notes_editor, .questions_editor", parent).css("min-height", height);

                        if($(this).hasClass("draft_question"))
                        {
                            $(this).summernote("disable");
                            $(".questions_editor", parent).addClass("locked");
                        }
                    },
                    onPaste: function(e) {
                        e.preventDefault();

                        hasChangesOnPage = true;
                        $(".unsaved_alert").show();

                        var html = (e.originalEvent || e).clipboardData.getData('text/html') || (e.originalEvent || e).clipboardData.getData('text/plain');
                        var dom = $("<div>" + html + "</div>");

                        $("*", dom).each(function() {
                            // Remove colgroup tag
                            if($(this).is("colgroup"))
                                $(this).remove();

                            // Remove style tag
                            if($(this).is("style"))
                                $(this).remove();

                            // Unwrap font tag if any
                            if($(this).is("font"))
                                $(this).contents().unwrap();

                            // Unwrap pre tag if any
                            if($(this).parent().is("pre"))
                                $(this).unwrap();

                            // Replace absolute urls by relative ones when using keyboard to paste
                            if($(this).is("a"))
                                $(this).attr("href", $(this).attr("title"));

                            // Fix when bold links come without spaces
                            if($(this).is("strong"))
                                $("<span> </span>").insertAfter($(this));

                            $(this).removeAttr("style")
                                .removeAttr("class")
                                .removeAttr("id")
                                .removeAttr("rel")
                                .removeAttr("title")
                                .removeAttr("bgcolor");
                        });

                        var container = $("<div>").append(dom.clone()).html();

                        window.document.execCommand('insertHtml', false, container);
                    },
                    onChange: function(contents, editable) {
                        hasChangesOnPage = true;
                        $(".unsaved_alert").show();
                    },
                    onKeydown: function(e) {
                        if (e.key == "<" || e.key == ">") {
                            e.preventDefault();
                        }
                    }
                }
            });
            $(".note_content a").each(function() {
                $(this).attr("title", $(this).attr("href"));
            });
        });
    });

    // Show/Hide help window
    $("#help_hide").click(function() {
        var $this = $(this).parents(".content_help");

        if($this.hasClass("open"))
        {
            $this.css("z-index", 102);
            $this.removeClass("open")
                .addClass("closed");
            $this.animate({right: -375}, 500, function() {
                $("#help_hide").removeClass("glyphicon-chevron-right")
                    .addClass("glyphicon-chevron-left");
            });
        }
        else
        {
            $this.css("z-index", 103);
            $this.removeClass("closed")
                .addClass("open");
            $this.animate({right: 0}, 500, function() {
                $("#help_hide").removeClass("glyphicon-chevron-left")
                    .addClass("glyphicon-chevron-right");
            });
        }
    });

    $(".button_copy_notes button").click(function(e) {
        e.preventDefault();

        $(this).data("pasted", true);

        $(".note-editable").html("");
        var content = $(".note_content").clone();
        $(".add_notes_editor").summernote("insertNode", content[0]);
    });

    // Profile form

    $("input[name^=prefered_roles]").change(function () {
        var role = $(this).val();
        var thisChecked = $(this).is(":checked");
        var trChecked = $(".tr_role").is(":checked");
        var fcChecked = $(".fc_role").is(":checked");
        var chChecked = $(".ch_role").is(":checked");

        if(thisChecked)
        {
            if(role == "facilitator")
            {
                $(".facilitator_section").show();
                $(".checker_section").show();
            }
            else
            {
                $(".checker_section").show();
            }
        }
        else
        {
            if(role == "facilitator")
            {
                $(".facilitator_section").hide();
                if(!trChecked && !fcChecked)
                    $(".checker_section").hide();
            }
            else
            {
                if(!trChecked && !fcChecked && !chChecked)
                    $(".checker_section").hide();
            }
        }
    });

    var langs = $(".langs option:selected");
    if(langs.length > 0)
        $(".langs").prop("disabled", false);

    $(".language").change(function() {
        var parent = $(this).closest(".language_block");
        if($(this).val() != "")
        {
            $(".fluency", parent).prop("name", "lang["+$(this).val()+"][fluency]").prop("disabled", false);
            //$(".geo_years", parent).prop("name", "lang["+$(this).val()+"][geo_years]").prop("disabled", false);
        }
        else
        {
            $(".fluency", parent).prop("name", "").prop("disabled", true);
            //$(".geo_years", parent).prop("name", "").prop("disabled", true);
        }
    });

    $(".language-close").click(function() {
        $(".language_container").css("left", "-9999px");
    });

    $(".language_add").click(function() {
        $(".language_container").css("left", 0);

        $(".language").val("").trigger('chosen:updated');
        $(".fluency").prop("checked", false).prop("disabled", true);
        //$(".geo_years").prop("checked", false).prop("disabled", true);
        $(".fluency/*, .geo_years*/").trigger("change");
        $(".language").chosen();
    });


    $(".add_lang").click(function() {
        var lang = $(".language").val();
        var langName = $(".language option:selected").text();
        var fluency = $(".fluency:checked").val();
        //var geo_years = $(".geo_years:checked").val();
        var option = $(".langs option[value^='"+lang+":']");

        if(option.length <= 0) {
            $(".langs").append("<option value='"+lang+":"+fluency+/*":"+geo_years+*/"'>" + langName + "</option>");
            option = $(".langs option[value^='"+lang+":']");
        }
        else
        {
            option.val(lang+":"+fluency+":"+geo_years)
        }

        option.prop("selected", true);

        $(".language_container").css("left", "-9999px");

        $(".langs").prop("disabled", false).trigger("chosen:updated");
    });

    $(".fluency/*, .geo_years*/").change(function() {
        var fluency = $(".fluency:checked").val();
        //var geo_years = $(".geo_years:checked").val();

        if(typeof fluency != "undefined"/* && typeof geo_years != "undefined"*/)
        {
            $(".add_lang").prop("disabled", false);
        }
        else
        {
            $(".add_lang").prop("disabled", true);
        }
    });

    $(".langs option").each(function() {
        var val = $(this).val();
        var langs = val.split(":");

        if(langs.length != 3) {
            $(this).remove();
            return true
        }

        $(this).text($(".language option[value="+langs[0]+"]").text());
    });

    // Mast events number test
    $("input[name=mast_evnts]").change(function() {
        if($(this).val() > 1)
        {
            $("input[name^='mast_role']").prop("disabled", false);
        }
        else
        {
            $("input[name^='mast_role']").prop("disabled", true);
        }
    });

    if($("input[name=mast_evnts]:checked").val() > 1)
    {
        $("input[name^='mast_role']").prop("disabled", false);
    }
    else
    {
        $("input[name^='mast_role']").prop("disabled", true);
    }


    // Facilitator test
    $("input[name=mast_facilitator]").change(function() {
        if($(this).val() == 1)
        {
            $("input[name=org]").prop("disabled", false);
            $("input[name=ref_person]").prop("disabled", false);
            $("input[name=ref_email]").prop("disabled", false);
        }
        else
        {
            $("input[name=org]").prop("disabled", true);
            $("input[name=ref_person]").prop("disabled", true);
            $("input[name=ref_email]").prop("disabled", true);
        }
    });

    if($("input[name=mast_facilitator]:checked").val() > 0)
    {
        $("input[name=org]").prop("disabled", false);
        $("input[name=ref_person]").prop("disabled", false);
        $("input[name=ref_email]").prop("disabled", false);
    }
    else
    {
        $("input[name=org]").prop("disabled", true);
        $("input[name=ref_person]").prop("disabled", true);
        $("input[name=ref_email]").prop("disabled", true);
    }

    // Event information accordion
    $(document).on("click", ".section_header", function() {
        var content = $(this).next(".section_content");
        var isCollapsed = $(".section_arrow", $(this)).hasClass("glyphicon-triangle-right");
        var opened = $(".chapter_list").data("opened") || 0;

        if(!isCollapsed)
        {
            content.hide("blind", {direction: "vertical"}, 300);
            $(".section_arrow", $(this))
                .removeClass("glyphicon-triangle-bottom")
                .addClass("glyphicon-triangle-right");

            $(".chapter_list").data("opened", opened - 1);

            if($(".chapter_list").data("opened") <= 0)
                $(".members_list").show("blind", {direction: "right"}, 300);

        }
        else
        {
            content.show("blind", {direction: "vertical"}, 300);
            $(".section_arrow", $(this))
                .removeClass("glyphicon-triangle-right")
                .addClass("glyphicon-triangle-bottom");

            $(".chapter_list").data("opened", opened + 1);

            $(".members_list").hide("blind", {direction: "right"}, 300);
        }
    });

    var searchTimeout;
    $("#add_checker").keyup(function (event) {
        $this = $(this);
        $("#checker_value").val("");
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            var name = $this.val();
            if(name.trim() == "")
            {
                $(".user_checkers").html("");
                return;
            }

            $.ajax({
                url: "/members/search",
                method: "post",
                data: {
                    name: name,
                    ext: true,
                },
                dataType: "json",
                beforeSend: function() {
                    $(".membersSearch").show();
                }
            })
                .done(function(data) {
                    $(".user_checkers").html("");
                    if(data.success)
                    {
                        if(data.count > 0)
                            $(".user_checkers").show();
                        else
                            $(".user_checkers").hide();

                        $.each(data.members, function () {
                            if(this.blocked == "1") return true;

                            var li = '<li>' +
                                '<label>' +
                                '<div class="chk_member" data="'+this.memberID+'">'+ this.firstName + ' ' + this.lastName +' ('+this.userName+')</div>' +
                                '</label>' +
                                '</li>';

                            $(".user_checkers").append(li);
                        });
                    }
                    else
                    {
                        debug(data.error);
                    }
                })
                .always(function () {
                    $(".membersSearch").hide();
                });
        }, 500);
    });

    $(document).on("click", ".chk_member", function () {
        var memberID = $(this).attr("data");
        $("#checker_value").val(memberID);
        $("#add_checker").val($(this).text());
        $(".user_checkers").hide();
    });

    $(".add_checker_btn").click(function () {
        var memberID = $("#checker_value").val();
        var chkName = $("#add_checker").val();

        if(chkName.trim() == "") return false;

        $.ajax({
            url: "/events/rpc/apply_verb_checker",
            method: "post",
            data: {
                eventID: eventID,
                chkName: chkName,
                chkID: memberID,
            },
            dataType: "json",
            beforeSend: function() {
                $(".membersSearch").show();
            }
        })
            .done(function(data) {
                $(".user_checkers").html("");
                if(data.success)
                {
                    $(".checker_name_span").text(data.chkName);
                    $(".add_cheker").remove();
                }
                else
                {
                    if(typeof isDemo != "undefined" && isDemo)
                    {
                        $(".checker_name_span").text(chkName.replace(/\(.*\)/, ""));
                        $(".add_cheker").remove();
                    }
                    else
                    {
                        debug(data.error);
                        renderPopup(data.error);
                    }
                }
            })
            .always(function () {
                $(".membersSearch").hide();
            });
    });

    // Check if event has been started
    if($("#evnt_state_checker").val() == "error")
    {
        setInterval(function () {
            $.ajax({
                url: "/events/rpc/check_event",
                method: "post",
                data: {
                    eventID: $("#evntid").val(),
                },
                dataType: "json",
            })
                .done(function(data) {
                    if(typeof data.success != "undefined" && data.success)
                    {
                        window.location.reload();
                    }
                    else
                    {
                        if(typeof data.error != "undefined")
                        {
                            window.location.href = "/";
                        }
                    }
                });
        }, 60000);
    }

    function localizeDate() {
        $.each($(".datetime"), function () {
            var dateStr = $(this).attr("data");
            if(dateStr == "" || dateStr == "----/--/--" || dateStr == "--:--") return true;

            var date_options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                weekday: 'short',
                timezone: 'UTC',
            };

            var time_options = {
                hour: 'numeric',
                minute: 'numeric',
                timezone: 'UTC',
            };

            if($(".event_time_time").length <= 0) // Combine date and time options for full result
            {
                date_options.hour = 'numeric';
                date_options.minute = 'numeric';
            }

            var lang = typeof getCookie("lang") != "undefined" ? getCookie("lang") : "en";

            var date = new Date(dateStr + " UTC");
            $(this).text(date.toLocaleString(lang, date_options));
            $(this).next(".event_time_time").text(date.toLocaleString(lang, time_options));
        });
    }

    localizeDate();

    // Dashboard tabs switch
    $(".my_tab").click(function () {
        var id = $(this).attr("id");

        $(".my_content").removeClass("shown");
        $(".my_tab").removeClass("active");

        $(this).addClass("active");
        $("#"+id+"_content").addClass("shown");

        return false;
    });

    if(!$(".my_tab:first").hasClass("active"))
        $(".my_tab:first").addClass("active");

    if(!$(".my_content:first").hasClass("shown"))
        $(".my_content:first").addClass("shown");


    // Show contact facilitator form
    $(".facil_names a").click(function() {
        var adminID = $(this).attr("data");
        var adminName = $(this).text();

        $(".mailer_name span").text(adminName);
        $(".adm_id").val(adminID);
        $(".mailer_container").css("left", 0);
        $(".mailer_form")[0].reset();

        return false;
    });

    $(".mailer-close").click(function() {
        $(".mailer_container").css("left", "-9999px");
    });

    $(".mailer_form").submit(function () {
        var form = $(this);

        $.ajax({
            url: "/members/rpc/send_message",
            method: "post",
            data: form.serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".mailer_button button")
                    .text(Language.sending)
                    .prop("disabled", true);
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $(".mailer_container").css("left", "-9999px");
                    renderPopup(Language.messageSendSuccess);
                }
                else
                {
                    if(typeof data.errorType != "undefined")
                    {
                        switch (data.errorType)
                        {
                            case "logout":
                            case "demo":
                            case "profile":
                                window.location.reload();
                                break;
                        }

                        if(typeof data.error != "undefined")
                            renderPopup(data.error);
                    }
                    else
                    {
                        $(".mailer_container").css("left", "-9999px");
                    }
                }
            })
            .error(function(xhr, status, error ) {
                renderPopup(status + ": " + error);
            })
            .always(function() {
                $(".mailer_button button")
                    .text(Language.send)
                    .prop("disabled", false);
            });

        return false;
    });

    $(window).scroll(function () {

        $this = $(this);

        if($(".save_profile_container").length > 0)
        {
            if($(this).scrollTop() > 70)
                $(".save_profile_container").removeClass("unlinked");
            else
                $(".save_profile_container").addClass("unlinked");
        }

        if($('.saildict_panel').length > 0)
        {
            var elementTop = $('.saildict_panel').offset().top;
            var elementBottom = elementTop + $('.saildict_panel').outerHeight();
            var viewportTop = $(window).scrollTop();
            var viewportBottom = viewportTop + $(window).height();

            if(viewportTop > elementTop)
                $(".saildict_panel").css("top", $this.scrollTop());
            else if(viewportBottom < elementBottom)
                $(".saildict_panel").css("top", elementTop - 150);
        }

        if($('.ttools_panel').length > 0)
        {
            $('.ttools_panel').each(function () {
                if($(this).is(":visible"))
                {
                    var elementTop = $(this).offset().top;
                    var elementBottom = elementTop + $(this).outerHeight();
                    var viewportTop = $(window).scrollTop();
                    var viewportBottom = viewportTop + $(window).height();

                    if(viewportTop > elementTop)
                        $(this).css("top", $this.scrollTop());
                    else if(viewportBottom < elementBottom)
                        $(this).css("top", elementTop - 150);
                }
            });
        }

        if($(".help_float").length > 0)
        {
            if($(this).scrollTop() > 150)
                $(".help_float").css("top", 10);
            else
                $(".help_float").css("top", 170 - $this.scrollTop());
        }
    });

    $("#refresh").click(function() {
        window.location.reload();
    });

    $(".demo_link, #demo_link").click(function(e) {
        if($(".demo_options").is(":visible"))
        {
            $(".demo_options").hide(200);
        }
        else
        {
            $(".demo_options").show(200);
        }

        e.stopPropagation();
    });

    $(document).click(function(e) {
        if(!e.target.classList.contains("demo_options"))
            $(".demo_options").hide(200);
    });

    // Sail dictionary
    $("body").on("keyup", "#sailfilter", function () {
        var w = escape($(this).val());
        var isGlobal = $("#sailfilter_global").is(":checked");
        var pattern = (!isGlobal ? "^" : "") + w;
        var re = new RegExp(pattern, "ig");

        $(".sail_list li").hide();
        $(".sail_list li").filter(function () {
            return this.id.match(re);
        }).show();
    });

    $("body").on("click", ".sail_list li", function () {
        var symbol = $("input", this);
        symbol.select();

        try
        {
            document.execCommand("Copy");
            $(".copied_tooltip").show().fadeOut(2000);
        }
        catch (error)
        {
            alert(error);
        }
    });

    // Faq filter
    $("body").on("keyup", "#faqfilter", function () {
        var w = $(this).val();
        var re = new RegExp(w, "ig");

        $(".faq_list li").hide();
        $(".faq_list li").filter(function () {
            return $(this).text().match(re);
        }).show();
    });

    // Translation tools
    $(".ttools").click(function (e) {
        $this = $(this);
        var tool = $(this).data("tool");

        if(tool == "sunbible")
        {
            var win = window.open("/translations/en/sun/ulb/", "_blank");
            win.focus();
            return;
        }

        var container = $(".ttools_panel."+tool+"_tool");

        var bookCode = $("input#bookCode").val();
        var chapter = $("input#chapter").val();
        var lang = $("input#"+tool+"_lang").val();
        var targetLang = $("input#targetLang").val();
        var totalVerses = $("input#totalVerses").val();

        if (container.length <= 0) {
            var query_params = (["tq","tn","tw","bc"].indexOf(tool) > -1
                ? bookCode + "/" + chapter + "/" + lang
                : (tool == "rubric" ? targetLang : ""))
                + (tool == "tn" ? "/" + totalVerses : "");

            $.ajax({
                url: "/events/rpc/get_" + tool + "/" + query_params,
                beforeSend: function() {
                    $this.prop("disabled", true);
                }
            })
                .done(function(data) {
                    $(".container_block").append(data);

                    container = $(".ttools_panel."+tool+"_tool");
                    if (container.length <= 0)
                    {
                        renderPopup(Language.resource_not_found);
                        return;
                    }

                    $(".ttools_panel").draggable({
                        snap: 'inner',
                        handle: '.panel-title',
                        containment: 'document'
                    });
                    container.css("top", $(window).scrollTop() + 50).show();
                })
                .error(function(xhr, status, error ) {
                    renderPopup(status + ": " + error);
                })
                .always(function() {
                    $this.prop("disabled", false);
                });
        }
        else
        {
            $(".ttools_panel").draggable({snap: 'inner', handle: '.panel-title'});
            container.css("top", $(window).scrollTop() + 50).show();
        }

        e.preventDefault();
    });

    // Show/Hide Keyword Definition
    $("body").on("click", ".word_term", function () {
        var word = $("span", this).text();
        var def = $(this).next(".word_def").html();
        var parent = $(this).closest(".ttools_content");

        $(".word_def_title", parent).text(word);
        $(".word_def_content", parent).html(def);

        //$(".labels_list").children().hide();
        $(".word_def_popup", parent).show("slide", {direction: "left"}, 300);
    });

    $("body").on("click", ".word_def-close", function() {
        $(".labels_list").children().show();
        var parent = $(this).closest(".ttools_content");

        $(".word_def_content", parent)[0].scrollTop = 0;
        $(".word_def_popup", parent).hide("slide", {direction: "left"}, 300);
    });

    // Show/hide original/english content of a rubric
    $("body").on("click", ".read_rubric_tabs li", function(e) {
        e.preventDefault();
        var id = $(this).attr("id");
        $(this).addClass("active");

        if(id == "tab_orig")
        {
            $("#tab_eng").removeClass("active");
            $(".read_rubric_qualities .orig").show();
            $(".read_rubric_qualities .eng").hide();
        }
        else
        {
            $("#tab_orig").removeClass("active");
            $(".read_rubric_qualities .orig").hide();
            $(".read_rubric_qualities .eng").show();
        }
    });

    $("body").on("click", ".bc-article", function (e) {
        e.preventDefault();
        
        var url = $(this).attr("href");
        var lang = $("#bc_lang").val();

        url = url.replace("\.\.\/articles/", "");

        var container = $(".ttools_panel.bc_article_tool");
        var opened = $(".ttools_panel.bc_article_tool[data-url='"+url+"']").length > 0;

        if (!opened)
        {
            $.get(`/events/rpc/get_bc_article/${ lang }/${ url }`, function (data) {

                $(".container_block").append(data);

                container = $(".ttools_panel.bc_article_tool");
                container.attr("data-url", url);

                if (container.length <= 0) {
                    renderPopup(Language.resource_not_found);
                    return;
                }

                $(".ttools_panel").draggable({
                    snap: 'inner',
                    handle: '.panel-title',
                    containment: 'document'
                });
                container.css("top", $(window).scrollTop() + 50).show();
            })
        }

        return;
    });

    $("body").on("click", ".ttools_panel .panel-close", function () {
        var tool = $(this).data("tool");
        $(".ttools_panel."+tool+"_tool").hide();
    });

    // ################ Question Editor ############### //

    $(".consume_q, .verbalize_q, .draft_q").click(function () {
        var parent = $(this).closest(".parent_q");
        var verse = parent.data("verse");
        var chapter = parent.data("chapter");
        var event = parent.data("event");
        var checked = $(this).is(":checked");
        var type = $(this).attr("class");
        var text = $(".add_questions_editor", parent).val();

        if(checked)
        {
            switch (type) {
                case "consume_q":
                    if(event > 0)
                        window.localStorage.setItem("consume_" + event + "_" + chapter + "_" + verse, "done");
                    $(".verbalize_q", parent).prop("disabled", false);
                    return true;
                case "verbalize_q":
                    if(event > 0)
                        window.localStorage.setItem("verbalize_" + event + "_" + chapter + "_" + verse, "done");
                    $(".draft_q", parent).prop("disabled", false);
                    $(".add_questions_editor", parent).summernote("enable");
                    $(".questions_editor", parent).removeClass("locked");
                    return true;
                case "draft_q":
                    if(text != "")
                    {
                        if(event > 0)
                            window.localStorage.setItem("draft_" + event + "_" + chapter + "_" + verse, "done");
                        return true;
                    }
                    break;
            }
        }

        return false;
    });

    $(".consume_q, .verbalize_q, .draft_q").each(function () {
        var parent = $(this).closest(".parent_q");
        var verse = parent.data("verse");
        var chapter = parent.data("chapter");
        var event = parent.data("event");
        var type = $(this).attr("class");
        var text = $(".add_questions_editor", parent).val();

        if(event == 0) return true;

        switch (type) {
            case "consume_q":
                var consume = localStorage.getItem("consume_" + event + "_" + chapter + "_" + verse);
                if(consume == "done")
                {
                    $(".consume_q", parent).prop("checked", true);
                    $(".verbalize_q", parent).prop("disabled", false);
                }
                break;
            case "verbalize_q":
                var verbalize = localStorage.getItem("verbalize_" + event + "_" + chapter + "_" + verse);
                if(verbalize == "done")
                {
                    $(".verbalize_q", parent).prop("checked", true);
                    $(".draft_q", parent).prop("disabled", false);

                    // Wait until summernote plugin loads completely
                    setTimeout(function () {
                        $(".add_questions_editor", parent).summernote("enable");
                        $(".questions_editor", parent).removeClass("locked");
                    }, 1000);
                }
                break;
            case "draft_q":
                var draft = localStorage.getItem("draft_" + event + "_" + chapter + "_" + verse);
                if(draft == "done")
                    $(".draft_q", parent).prop("checked", true);
                break;
        }
    });

    // Super admin local login
    $("#isSuperAdmin").change(function () {
        if($(this).is(":checked"))
        {
            $("#password").val("");
            $(".password_group").show();
        }
        else
        {
            $("#password").val("default");
            $(".password_group").hide();
        }
    });

    // ------------- Language Input ------------- //

    // Autosave verse in scripture input mode
    $(document).on("keyup paste change", ".input_mode_ta", function() {
        if (hasInputModeChangesOnPage) {
            inputModeChangesQueued = true;
        }
        hasInputModeChangesOnPage = true;
        $(".unsaved_alert").show();
    });

    var inputModeAutosaver = setInterval(function() {
        if(typeof isDemo != "undefined" && isDemo) {
            hasInputModeChangesOnPage = false;
            $(".unsaved_alert").hide();
        }

        if(hasInputModeChangesOnPage) {
            let postData = $("#main_form").serializeArray();
            postData.push({"name":"eventID","value":eventID});

            $.ajax({
                url: "/events/rpc/autosave_im_verse",
                method: "post",
                data: postData,
                dataType: "json"
            })
                .done(function(data) {
                    if(data.success) {
                        $(".unsaved_alert").hide();
                        if (!inputModeChangesQueued) {
                            hasInputModeChangesOnPage = false;
                        } else {
                            inputModeChangesQueued = false;
                        }
                    } else {
                        if(typeof data.errorType != "undefined") {
                            switch (data.errorType) {
                                case "logout":
                                    window.location.href = "/members/login";
                                    break;

                                case "checkDone":
                                    hasInputModeChangesOnPage = false;
                                    inputModeChangesQueued = false;
                                    renderPopup(data.error);
                                    $(".unsaved_alert").hide();
                                    break;

                                case "json":
                                    hasInputModeChangesOnPage = false;
                                    inputModeChangesQueued = false;
                                    renderPopup(data.error);
                                    $(".unsaved_alert").hide();
                                    break;
                            }
                        }
                        console.log(data.error);
                    }
                })
                .error(function (xhr, status, error) {
                    debug(status);
                    debug(error);
                    hasInputModeChangesOnPage = false;
                })
                .always(function() {

                });
        }
    }, 3000);

    // Export menu
    $("#upload_menu span").click(function(e) {
        e.stopPropagation();
        if(!$("#upload_menu ul").is(":visible")) {
            $("#upload_menu ul").slideDown(200);
        } else {
            $("#upload_menu ul").slideUp(200);
        }
    });

    $("body").click(function() {
        if($("#upload_menu ul").is(":visible")) {
            $("#upload_menu ul").slideUp(200);
        }
    });

    $(".export_cloud a").click(function(e) {
        const url = $(this).prop("href");
        exportToCloud(url);
        e.preventDefault();
    });

    $(".obs_img").click(function() {
        const src = $(this).data("img");
        const box = $('<div class="obs_img_box">' +
            '<span class="mdi mdi-close obs_img_close"></span>' +
            '<img class="obs_img_floatable" src="'+src+'" />' +
            '</div>');
        box.css("top", $(this).offset().top);

        box.appendTo($("body"));
        $(".obs_img_box").draggable({
            snap: 'inner',
            containment: 'document'
        });
    });

    $("body").on("click", ".obs_img_close", function() {
        $(this).parent(".obs_img_box").remove();
    });

    $(".demo_sun_mode input").change(function() {
        const checked = $(this).prop('checked');
        if (checked) {
            setCookie("sun_mode", 1, { path: "/" });
        } else {
            deleteCookie("sun_mode");
        }
        window.location.reload();
    });
});

function exportToCloud(url) {
    const dialog = renderPopup(Language.sending, null, null, false);

    $.ajax({
        url: url,
        method: "get",
        dataType: "json"
    })
        .done(function(data) {
            if(data.success) {
                dialog.dialog("destroy");
                renderPopup(Language.pushSuccess + " <a href='"+data.url+"' target='_blank'>"+data.url+"</a>");
            } else {
                dialog.dialog("destroy");
                if(typeof data.authenticated != "undefined" && !data.authenticated) {
                    const title = Language.authorizeServer.formatUnicorn({
                        "repo_server": data.server.toUpperCase()
                    });
                    renderPopup(title, function () {
                        window.open(data.url);
                        const checkDialog = renderPopup(Language.authorizing);
                        const checkInterval = setInterval(function() {
                            $.get(`/members/oauth_check/${data.server}`, function(data) {
                                if (typeof data.success !== "undefined" && data.success) {
                                    clearInterval(checkInterval);
                                    checkDialog.dialog("destroy");
                                    exportToCloud(url);
                                }
                            }, "json");
                        }, 3000);
                    });
                } else if(typeof data.error != "undefined") {
                    renderPopup(data.error);
                }
            }
        })
        .error(function (xhr, status, error) {
            debug(status);
            debug(error);
        })
        .always(function() {

        });
}

function animateIntro() {
    var  grl=$( "#ground-left" );
    var  grr=$( "#ground-right" );
    var  grc=$( "#ground-center" );
    var  cll=$( "#cloud-left" );
    var  clr=$( "#cloud-right" );

    grc.delay(0).animate(
        {bottom: 0},1300, "linear");
    grl.delay(400).animate(
        {bottom: 0},1400, "linear");
    grr.delay(400).animate(
        {bottom: 0},1400, "linear"); //easeOutBounce

    cll.delay(1300).animate({  attrRotate: 90 }, {
        step: function(now,fx) {
            tt = (now-90)/90;
            $(this).css('-webkit-transform','rotate('+(tt*28)+'deg)');
            $(this).css('-moz-transform','rotate('+(tt*28)+'deg)');
            $(this).css('-ms-transform','rotate('+(tt*28)+'deg)');
            $(this).css('-o-transform','rotate('+(tt*28)+'deg)');
            $(this).css('transform','rotate('+(tt*28)+'deg)');
            $(this).css('bottom',(-(tt)*(tt)*50)+'%');
            $(this).css('left',(tt*33)+'%');
        },
        duration:1300
    },'linear');
    clr.delay(1300).animate({  attrRotate: 90 }, {
        step: function(now,fx) {
            tt = (now-90)/90;
            $(this).css('-webkit-transform','rotate('+(-tt*28)+'deg)');
            $(this).css('-moz-transform','rotate('+(-tt*28)+'deg)');
            $(this).css('-ms-transform','rotate('+(-tt*28)+'deg)');
            $(this).css('-o-transform','rotate('+(-tt*28)+'deg)');
            $(this).css('transform','rotate('+(-tt*28)+'deg)');
            $(this).css('bottom',(-(tt)*(tt)*50)+'%');
            $(this).css('right',(tt*33)+'%');
        },
        duration:1300
    },'linear');
}

// Cookie Helpers
/**
 * Get cookie by name
 * @param string name
 * @returns {*}
 */
function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

/**
 * Set cookie value by name
 * @param name
 * @param value
 * @param options
 */
function setCookie(name, value, options) {
    options = options || {};

    let expires = options.expires;

    if (typeof expires == "number" && expires) {
        const d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    let updatedCookie = name + "=" + value;

    for (const propName in options) {
        updatedCookie += "; " + propName;
        const propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

/**
 * Delete cookie by name (make it expired)
 * @param name
 * @param options
 */
function deleteCookie(name, options) {
    options = options || {};
    const path = options.path || "/";
    setCookie(name, "", {
        expires: -1,
        path: path
    })
}

function parseCombinedVerses(verse)
{
    var versesArr = [];
    var verses = verse.split("-");

    if(verses.length < 2)
    {
        versesArr.push(parseInt(verse));
        return versesArr;
    }

    var fv = parseInt(verses[0]);
    var lv = parseInt(verses[1]);

    for(var i=fv; i <= lv; i++)
    {
        versesArr.push(i);
    }

    return versesArr;
}

function unEscapeStr(string) {
    return $('<div/>').html(string).text();
}

function escapeQuotes(str) {
    return str.replace(/"/g, "&quot;").replace(/'/g, "&apos;");
}

/**
 * Renders and shows dialog window with OK button
 * @param message
 * @param onOK Ok button callback
 * @param onClose Close button callback
 * @param closable Popup is closable
 * @returns {boolean}
 */
function renderPopup(message, onOK, onClose, closable) {
    let buttons = {};
    let dialogClass = "no-close";

    closable = typeof closable != "undefined" ? closable : true;

    if(closable) {
        onOK = typeof onOK != "undefined" ? onOK : function(){
            $(this).dialog("close");
        };

        onClose = typeof onClose != "undefined" ? onClose : function(){
            $(this).dialog("close");
            return false;
        };

        buttons = {
            Ok: onOK
        };

        dialogClass = "";
    }

    $(".message_content").html(message);

    return $( "#dialog-message" ).dialog({
        dialogClass: dialogClass,
        modal: true,
        resizable: false,
        draggable: false,
        width: 500,
        buttons: buttons,
        close: onClose,
        beforeClose: function(){
            return closable;
        }
    });
}

/**
 * Renders and shows confirm dialog popup
 * @param title
 * @param message
 * @param onAnswerYes positive answer callback
 * @param onAnswerNo Negative answer callback
 * @param onClose close dialog callback
 */
function renderConfirmPopup(title, message, onAnswerYes, onAnswerNo, onClose) {
    onAnswerYes = typeof onAnswerYes != "undefined" ? onAnswerYes : function(){
        $( this ).dialog( "close" );
        return true;
    };
    onAnswerNo = typeof onAnswerNo != "undefined" ? onAnswerNo : function(){
        $( this ).dialog( "close" );
        return false;
    };
    onClose = typeof onClose != "undefined" ? onClose : function(){
        $( this ).dialog( "close" );
        return false;
    };

    var yes = Language.yes;
    var no = Language.no;

    var btns = {};
    btns[yes] = onAnswerYes;
    btns[no] = onAnswerNo;

    $(".confirm_message").html(message);
    $( "#check-book-confirm" ).dialog({
        resizable: false,
        draggable: false,
        title: title,
        height: "auto",
        width: 500,
        modal: true,
        buttons: btns,
        close: onClose,
    });
}

function decrementNotifications() {
    let notifications = parseInt($(".notif_count").text());
    notifications--;
    if(notifications <= 0)
    {
        $(".notif_count").remove();
        const notificationBlock = '<div class="no_notif">' + Language.noNotifsMsg + '</div>';
        $(".notif_block").html(notificationBlock);
    } else {
        $(".notif_count").text(notifications);
    }
}

function saveOrRemoveKeyword(verseID, text, index, remove) {
    remove = remove || false;
    const verseData = verseID.split("_");

    const chapter = verseData[1];
    const chunk = verseData[2];
    const verse = verseData[3];

    if(typeof isDemo != "undefined") {
        highlightKeyword(verseID, text, index, remove);
        return false;
    }

    $.ajax({
        url: "/events/rpc/save_keyword",
        method: "post",
        data: {
            eventID: eventID,
            chapter: chapter,
            chunk: chunk,
            verse: verse,
            index: index,
            text: text,
            remove: remove
        },
        dataType: "json",
        beforeSend: function() {
            $(".chunk_verses").css("cursor", "wait");
            $(".chunk_verses b").css("cursor", "wait");
        }
    })
        .done(function(data) {
            if(data.success) {
                const data = {
                    type: "keyword",
                    remove: remove,
                    eventID: eventID,
                    chkMemberID: chkMemberID,
                    verseID: verseID,
                    text: text,
                    index: index,
                };

                highlightKeyword(verseID, text, index, remove);
                socket.emit("system message", data);
            } else {
                if(typeof data.error != "undefined") {
                    renderPopup(data.error, function () {
                        window.location.reload();
                    });
                }
            }
        })
        .always(function() {
            $(".chunk_verses").css("cursor", "auto");
            $(".chunk_verses b").css("cursor", "pointer");
        });
}

/**
 * Highlight or remove selection of the given keyword
 * @param verseID   Verse element
 * @param text      Keyword text
 * @param index     Order number of the keyword in the verse
 * @param remove    Whether to remove selection or not
 */
function highlightKeyword(verseID, text, index, remove) {
    remove = remove || false;
    const verseEl = $("." + verseID);
    index = parseInt(index);

    if(verseEl.length <= 0) return;

    text = unEscapeStr(text);

    if(remove) {
        $("b[data="+index+"]", verseEl)
            .filter(function() {
                return $(this).text() == text;
            })
            .contents()
            .unwrap();

        verseEl.html(function () {
            return $(this).html();
        })
    } else {
        const verseText = verseEl.html().trim();
        const regex = new RegExp("(" + text + ")", "gmi");

        let nth = -1;
        const html = verseText.replace(regex, function (match, i, orig) {
            nth++;
            return (nth === index)
                ? "<b data='" + index + "'>" + match + "</b>"
                : match;
        });
        verseEl.html(html);
    }
}

function createNotification(eventID, to, step, chapter, manageMode, type, loader) {
    loader = loader || $("<div>Loading...</div>").prepend("body");

    return $.ajax({
        url: "/events/rpc/create_notification",
        method: "post",
        data: {
            eventID,
            to,
            step,
            chapter,
            manageMode,
            type
        },
        dataType: "json",
        beforeSend: function() {
            loader.show();
        }
    })
        .error(function () {
            renderPopup(Language.commonError);
        })
        .always(function() {
            loader.hide();
        });
}

function convertRcLinks(html) {
    return html.replace(/\[\[rc:\/\/(.*?)\]\]/g, function(match, p1) {
        return convertLink(p1);
    })
}

function convertLink(rc) {
    let server = "https://git.door43.org/unfoldingWord/";
    let parts = rc.split("/");
    let lang = parts[0];
    let resource = parts[1];
    let git = "/src/branch/master/";
    let components = parts.slice(3, parts.length).join("/");
    let title = lang + "_" + resource + ": " + parts[parts.length-1];

    let ending = ".md";
    if (resource == "ta") ending = "/01.md";

    let link = server + lang + "_" + resource + git + components + ending;

    return "<a href='"+link+"' target='_blank'>"+title+"</a>";
}

// Download link for cv (csv, tsv, etc) file
function downloadSV(sv, filename, format) {
    var svFile;
    var downloadLink;

    // SV file
    svFile = new Blob([sv], {type: "application/" + format});

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // Create a link to the file
    downloadLink.href = window.URL.createObjectURL(svFile);

    // Hide download link
    downloadLink.style.display = "none";

    // Add the link to DOM
    document.body.appendChild(downloadLink);

    // Click download link
    downloadLink.click();
}

// Export table as cv (csv, tsv, etc) content
function exportTableToSV(parent, separator) {
    var sv = [];
    var rows = parent.querySelectorAll("table tr");

    for (var i = 0; i < rows.length; i++) {
        var row = [], cols = rows[i].querySelectorAll("td, th");

        for (var j = 0; j < cols.length; j++)
            row.push(cols[j].innerText);

        sv.push(row.join(separator));
    }

    // return SV content
    return sv.join("\n");
}

/**
 * Detect if current browser is Internet Explorer/Edge
 * @returns {boolean}
 */
function isIE() {
    if (/MSIE 10/i.test(navigator.userAgent)) {
        // this is internet explorer 10
        return true;
    }

    if(/MSIE 9/i.test(navigator.userAgent) || /rv:11.0/i.test(navigator.userAgent)){
        // this is internet explorer 9 and 11
        return true;
    }

    if (/Edge\/12./i.test(navigator.userAgent)){
        // this is Microsoft Edge
        return true;
    }

    return false;
}

function debug(obj, stop) {
    stop = stop || false;
    console.log(obj);
    if(stop)
        throw new Error("debug stop!");
}

jQuery.fn.deserialize = function (data) {
    var f = this,
        map = {},
        find = function (selector) { return f.is("form") ? f.find(selector) : f.filter(selector); };
    //Get map of values
    jQuery.each(data.split("&"), function () {
        var nv = this.split("="),
            n = decodeURIComponent(nv[0]),
            v = nv.length > 1 ? decodeURIComponent(nv[1]) : null;
        if (!(n in map)) {
            map[n] = [];
        }
        map[n].push(v);
    })
    //Set values for all form elements in the data
    jQuery.each(map, function (n, v) {
        find("[name='" + n + "']").val(v);
    })
    //Clear all form elements not in form data
    find("input:text,select,textarea").each(function () {
        if (!(jQuery(this).attr("name") in map)) {
            jQuery(this).val("");
        }
    })
    find("input:checkbox:checked,input:radio:checked").each(function () {
        if (!(jQuery(this).attr("name") in map)) {
            this.checked = false;
        }
    })
    return this;
};
