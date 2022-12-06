/**
 * Created by Maxim on 05 Feb 2016.
 */

// ------------------ Jquery Events --------------------- //

const AssignChapterConfirm = {
    ASSIGN: 1,
};

$(function () {

    $(".panel-close").click(function() {
        $(this).parents(".form-panel").css("left", "-9999px");
    });

    // Show assign chapter dialog
    $(".add_person_chapter").click(function() {
        const chapterTitle = $(".chapter_members_div .panel-title span");
        const mode = $("#mode").val();
        let chapter = $(this).data("chapter");
        let chapterName = chapter;
        if(mode === "tw") {
            chapterName = $(this).data("group");
        }

        chapterTitle.text(chapterName).data("chapter", chapter);

        $(".chapter_members").show();

        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });
    });


    // Close assign chapter dialog
    $(".chapter-members-close").click(function() {
        $(".chapter_members").hide();

        $('html, body').css({
            'overflow': 'auto',
            'height': 'auto'
        });
    });

    // Assign chapter to translator/checker
    $(document).on("click", ".assign_chapter", function() {
        const chapterTitle = $(".chapter_members_div .panel-title span");

        const data = {
            "eventID": $("#eventID").val(),
            "chapter": chapterTitle.data("chapter"),
            "memberID": $(this).data("userid"),
            "memberName": $(this).data("username"),
            "manageMode": typeof manageMode != "undefined" ? manageMode : "l1",
        };
        assignChapter(data, "add", AssignChapterConfirm.ASSIGN);
    });

    // Show "add translator/checker" dialog
    $("#openMembersSearch").click(function() {
        $(".user_translators").html("");
        $("#user_translator").val("");

        $(".members_search_dialog").show();

        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });
    });


    // Close "add translator/checker" dialog
    $(".members-search-dialog-close").click(function() {
        $(".user_translators").html("");
        $("#user_translator").val("");

        $(".members_search_dialog").hide();

        $('html, body').css({
            'overflow': 'auto',
            'height': 'auto'
        });

        window.location.reload();
    });

    let searchTimeout;
    $("#user_translator").keyup(function (event) {
        $this = $(this);
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function () {
            const name = $this.val();
            if(name.trim() === "") {
                $(".user_translators").html("");
                return;
            }

            $.ajax({
                url: "/members/search",
                method: "post",
                data: {
                    name: name,
                    ext: true,
                    verified: true
                },
                dataType: "json",
                beforeSend: function() {
                    $(".openMembersSearch.dialog_f").show();
                }
            })
                .done(function(data) {
                    $(".user_translators").html("");
                    if(data.success) {
                        $.each(data.members, function () {
                            const exist = $(".assign_chapter[data-userid="+this.memberID+"]");
                            if(exist.length > 0) return true;
                            if(this.blocked === "1") return true;

                            const li = '<li>' +
                                '<label>' +
                                    '<div class="tr_member">'+ this.firstName + ' ' + this.lastName +' ('+this.userName+')</div>' +
                                    '<div class="form-group tr_member_add">' +
                                        '<button class="btn btn-primary add_translator" data-userid="'+this.memberID+'">'+Language.add+'</button>' +
                                    '</div>' +
                                '</label>' +
                            '</li>';

                            $(".user_translators").append(li);
                        });
                    } else {
                        debug(data.error);
                    }
                })
                .always(function () {
                    $(".openMembersSearch.dialog_f").hide();
                });
        }, 500);
    });

    $(document).on("click", ".add_translator", function () {
        const $this = $(this);
        const memberID = $(this).data("userid");
        const eventID = $("#eventID").val();

        $.ajax({
            url: "/events/rpc/add_event_member",
            method: "post",
            data: {
                memberID: memberID,
                eventID: eventID,
                userType: userType
            },
            dataType: "json",
            beforeSend: function() {
                $(".openMembersSearch.dialog_f").show();
            }
        })
            .done(function(data) {
                if(data.success) {
                    $this.parents("li").remove();
                    sendUserEmail(eventID, memberID, null);
                } else {
                    const errorMsg = typeof data.errors !== "undefined" ? " ("+Object.values(data.errors).join(", ")+")" : "";
                    renderPopup(data.error + errorMsg);
                }
            })
            .always(function () {
                $(".openMembersSearch.dialog_f").hide();
            });
    });

    // Show "Create words group" dialog
    $("#word_group_create").click(function() {
        $("#word_group").val("");

        $(".words_group_dialog").show();

        $('html, body').css({
            'overflow': 'hidden',
            'height': '100%'
        });
    });

    // Close "Create words group" dialog
    $(".words-group-dialog-close").click(function() {
        $(".words_group_dialog").hide();

        $('html, body').css({
            'overflow': 'auto',
            'height': 'auto'
        });
    });

    // Create a group of translation words
    $(document).on("click", "#create_group", function () {

        var group = $("#word_group").val();
        var eventID = $("#eventID").val();

        $.ajax({
            url: "/events/rpc/create_words_group",
            method: "post",
            data: {
                group: group,
                eventID: eventID
            },
            dataType: "json",
            beforeSend: function() {
                $(".openWordsGroup.dialog_f").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    $(".words_group_dialog").hide();
                    window.location.reload();
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".openWordsGroup.dialog_f").hide();
            });
    });

    // Delete a group of translation words
    $(document).on("click", ".group_delete", function () {
        var groupID = $(this).data("groupid");
        var eventID = $("#eventID").val();
        var $this = $(this);

        renderConfirmPopup(Language.deleteGroupConfirmTitle, Language.deleteGroupConfirm, function () {
            $( this ).dialog( "close" );

            $.ajax({
                url: "/events/rpc/delete_words_group",
                method: "post",
                data: {
                    groupID: groupID,
                    eventID: eventID
                },
                dataType: "json",
                beforeSend: function() {
                    $this.css("background-color", "#f00");
                }
            })
                .done(function(data) {
                    if(data.success)
                    {
                        $(".words_group_dialog").hide();
                        window.location.reload();
                    }
                    else
                    {
                        if(typeof data.error != "undefined")
                        {
                            renderPopup(data.error);
                        }
                    }
                })
                .always(function() {
                    $this.css("background-color", "#666666");
                });
        }, function () {
            $( this ).dialog( "close" );
        });
    });

    $(".chapter_menu li").click(function() {
        const id = $(this).data("id");
        const memberID = $(this).data("userid");
        const chapter = $(this).data("chapter");
        $(".main_menu ul").hide();

        switch (id) {
            case "move_back":
                moveStepBackHandler(memberID, chapter);
                break;
            case "remove_chapter":
                const userName = $(this).data("username");
                removeChapter(chapter, memberID, userName);
                break;
        }
    });

    // Remove chapter from user
    function removeChapter(chapter, userID, userName) {
        renderConfirmPopup(Language.deleteChapterConfirmTitle, Language.deleteChapterConfirm, function () {
            $( this ).dialog( "close" );

            const data = {
                "eventID": $("#eventID").val(),
                "chapter": chapter,
                "memberID": userID,
                "memberName": userName,
                "manageMode": typeof manageMode != "undefined" ? manageMode : "l1"
            };
            assignChapter(data, "delete");
        }, function () {
            $( this ).dialog( "close" );
        });
    }

    $(".user_menu li").click(function() {
        const id = $(this).data("id");
        const userID = $(this).data("userid");
        const eventID = $("#eventID").val();
        $(".main_menu ul").hide();

        switch (id) {
            case "set_checker":
                const checkbox = $("input", $(this));
                setChecker(userID, eventID, checkbox);
                break;
            case "delete_user":
                deleteUser(eventID, userID);
                break;
        }
    });

    // Remove member from event
    function deleteUser(eventID, userID) {
        renderConfirmPopup(Language.removeFromEvent, Language.deleteMemberConfirm, function () {
            $(this).dialog( "close" );

            $.ajax({
                url: "/events/rpc/delete_event_member",
                method: "post",
                data: {eventID: eventID, memberID: userID, manageMode: manageMode},
                dataType: "json",
                beforeSend: function() {
                    $(".user_menu_loader[data-userid="+userID+"]").show();
                }
            })
                .done(function(data) {
                    if(data.success) {
                        $(".member_username[data-userid="+userID+"]").parents("li").remove();
                        $(".assign_chapter[data-userid="+userID+"]").parents("li").remove();

                        let mNum = parseInt($(".manage_members h3 span").text()); // number of current members
                        mNum -= 1;
                        $(".manage_members h3 span").text(mNum);
                    } else {
                        if(typeof data.error !== "undefined") {
                            renderPopup(data.error);
                        }
                        console.log(data.error);
                    }
                })
                .always(function() {
                    $(".user_menu_loader[data-userid="+userID+"]").hide();
                });
        }, function () {
            $(this).dialog("close");
        });
    }

    // Start interval to check new applied translators
    setInterval(function () {
        getNewMembersList();
    }, 300000);

    function getNewMembersList() {
        const eventID = $("#eventID").val();
        const ids = [];

        if(typeof isManagePage === "undefined") return false;
        if(typeof eventID === "undefined" || eventID === "") return false;

        $.each($(".assign_chapter"), function() {
            ids.push($(this).data("userid"));
        });

        $.ajax({
            url: "/events/rpc/get_event_members",
            method: "post",
            data: {eventID: eventID, memberIDs: ids, manageMode: manageMode},
            dataType: "json"
        })
            .done(function(data) {
                if(data.success) {
                    const newUsers = [];
                    $.each(data.members, function(index, value) {
                        const hiddenListLi = '<li>'+
                            '   <div class="member_username userlist chapter_ver">'+
                            '       <div class="divname">'+value.firstName+' '+value.lastName.charAt(0)+'.</div>'+
                            '       <div class="divvalue">(<span>0</span>)</div>'+
                            '   </div>'+
                            '   <button class="btn btn-success assign_chapter" ' +
                            '       data-userid="'+value.memberID+'" ' +
                            '       data-username="'+value.firstName+' '+value.lastName.charAt(0)+'.">'+Language.assign+'</button>'+
                            '   <div class="clear"></div>'+
                            '</li>';
                        $(".chapter_members_div ul").append(hiddenListLi);

                        const shownListLi = '<li>'+
                            '<div class="member_username" data-userid="'+value.memberID+'">'+
                                value.firstName+' '+value.lastName.charAt(0)+'. (<span>0</span>)'+
                            '</div>'+
                            '<div class="member_chapters">'+Language.chapters+': '+'</div>'+
                            '</li>';
                        $(".manage_members ul").append(shownListLi);

                        newUsers.push(value.firstName+' '+value.lastName.charAt(0)+'.');
                    });

                    if(newUsers.length > 0) {
                        let mNum = parseInt($(".manage_members h3 span").text()); // number of current members
                        mNum += newUsers.length;
                        $(".manage_members h3 span").text(mNum);
                    }
                } else {
                    console.log(data.error);
                }
            });
    }

    // Show info tip
    $(".create_info_tip a").click(function () {
        renderPopup($(".create_info_tip span").text());
        return false;
    });

    // Members search
    // Submit Filter form
    $(".filter_apply button").click(function () {
        var button = $(this);
        button.prop("disabled", true);
        $(".filter_page").val(1);

        if(/\/admin\/members/.test(window.location.pathname))
            return false;

        $.ajax({
            url: "/members/search",
            method: "post",
            data: $("#membersFilter").serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".filter_loader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    if(data.members.length > 0)
                    {
                        $("#all_members_table").show();
                        $(".filter_page").val(1);

                        // if it has more results to show draw "more" button
                        if(data.members.length < parseInt(data.count))
                        {
                            if($("#search_more").length <= 0)
                            {
                                $('<div id="search_more"></div>').appendTo("#all_members_content")
                                    .text(Language.searchMore);
                            }
                            $(".filter_page").val(2);
                        }
                        else
                        {
                            $("#search_more").remove();
                        }

                        $("#search_empty").remove();
                    }
                    else
                    {
                        $("#all_members_table").hide();
                        if($("#search_empty").length <= 0)
                            $('<div id="search_empty"></div>').appendTo("#all_members_content")
                                .text(Language.searchEmpty);
                        $('#search_more').remove();
                    }

                    $("#all_members_table tbody").html("");
                    $.each(data.members, function (i, v) {
                        var row = "<tr>" +
                            "<td><a href='/members/profile/"+v.memberID+"'>"+v.userName+"</a></td>" +
                            "<td>"+v.firstName+" "+v.lastName+"</td>" +
                            "<td>"+v.email+"</td>" +
                            "<td>"+(v.projects ? JSON.parse(v.projects).map(function (proj) {
                                return Language[proj];
                            }).join(", ") : "")+"</td>" +
                            "<td>"+(v.proj_lang ? "["+v.langID+"] "+v.langName +
                                (v.angName != "" && v.angName != v.langName ? " ("+v.angName+")" : "") : "")+"</td>" +
                            "<td><input type='checkbox' "+(parseInt(v.complete) ? "checked" : "")+" disabled></td>" +
                            "</tr>";
                        $("#all_members_table tbody").append(row);
                    });
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".filter_loader").hide();
                button.prop("disabled", false);
            });

        return false;
    });

    // Show more search members results
    $(document).on("click", "#search_more", function () {
        if(typeof isSuperAdmin != "undefined") return false;
        var button = $(this);

        if(button.hasClass("disabled")) return false;

        button.addClass("disabled");
        var page = parseInt($(".filter_page").val());

        $.ajax({
            url: "/members/search",
            method: "post",
            data: $("#membersFilter").serialize(),
            dataType: "json",
            beforeSend: function() {
                $(".filter_loader").show();
            }
        })
            .done(function(data) {
                if(data.success)
                {
                    if(data.members.length > 0)
                    {
                        $(".filter_page").val(page+1);
                        $.each(data.members, function (i, v) {
                            var row = "<tr>" +
                                "<td><a href='/members/profile/"+v.memberID+"'>"+v.userName+"</a></td>" +
                                "<td>"+v.firstName+" "+v.lastName+"</td>" +
                                "<td>"+v.email+"</td>" +
                                "<td>"+(v.projects ? JSON.parse(v.projects).map(function (proj) {
                                    return Language[proj];
                                }).join(", ") : "")+"</td>" +
                                "<td>"+(v.proj_lang ? "["+v.langID+"] "+v.langName +
                                    (v.angName != "" && v.angName != v.langName ? " ("+v.angName+")" : "") : "")+"</td>" +
                                "<td><input type='checkbox' "+(parseInt(v.complete) ? "checked" : "")+" disabled></td>" +
                                "</tr>";
                            $("#all_members_table tbody").append(row);
                        });

                        var results = parseInt($("#all_members_table tbody tr").length);
                        if(results >= parseInt(data.count))
                            $('#search_more').remove();
                    }
                    else
                    {
                        $('#search_more').remove();
                    }
                }
                else
                {
                    if(typeof data.error != "undefined")
                    {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".filter_loader").hide();
                button.removeClass("disabled");
            });
    });

    // Clear members filter
    $(".filter_clear").click(function () {
        $("#membersFilter")[0].reset();
        $(".mems_language").val('').trigger("chosen:updated");
        return false;
    });

    // Moving members a step back
    function moveStepBackHandler(memberID, chapter) {
        const eventID = $("#eventID").val();
        moveStepBack(eventID, memberID, chapter);
    }

    function moveStepBack(eventID, memberID, chapter, confirm) {
        confirm = confirm || 0;
        const mMode = typeof manageMode != "undefined" ? manageMode : "l1";

        $.ajax({
            url: "/events/rpc/move_step_back",
            method: "post",
            data: {
                eventID : eventID,
                memberID : memberID,
                chapter : chapter,
                confirm: confirm,
                manageMode: mMode
            },
            dataType: "json",
            beforeSend: function() {
                $(".chapter_menu_loader[data-chapter="+chapter+"]").show();
            }
        })
            .done(function(data) {
                if(data.success) {
                    renderPopup(data.message, function () {
                        window.location.reload();
                    }, function () {
                        window.location.reload();
                    });
                } else {
                    if(typeof data.error !== "undefined") {
                        renderPopup(data.error,
                            function () {
                                $( this ).dialog( "close" );
                            },
                            function () {
                                window.location.reload();
                            });
                        return;
                    }

                    if(typeof data.confirm !== "undefined") {
                        renderConfirmPopup(Language.attention, data.message,
                            function () {
                                moveStepBack(eventID, memberID, chapter, data.confirm);
                                $( this ).dialog( "close" );
                            },
                            function () {
                                $( this ).dialog( "close" );
                            });
                    }
                }
            })
            .always(function() {
                $(".chapter_menu_loader").hide();
            });
    }

    // Set checker role for tN project
    function setChecker(memberID, eventID, checkbox) {
        const $this = $(this);

        $.ajax({
            url: "/events/rpc/set_tn_checker",
            method: "post",
            data: {
                eventID : eventID,
                memberID : memberID,
            },
            dataType: "json",
            beforeSend: function() {
                $(".user_menu_loader[data-userid="+memberID+"]").show();
            }
        })
            .done(function(data) {
                if(data.success) {
                    checkbox.prop("checked", !checkbox.prop("checked"));
                } else {
                    if(typeof data.error !== "undefined") {
                        renderPopup(data.error);
                    }
                }
            })
            .always(function() {
                $(".user_menu_loader[data-userid="+memberID+"]").hide();
            });
    }
});


// --------------- Variables ---------------- //



// --------------- Functions ---------------- //
function assignChapter(data, action, confirm) {
    $(".alert.alert-danger, .alert.alert-success").remove();

    confirm = confirm || 0;

    $.ajax({
        url: "/events/rpc/assign_chapter",
        method: "post",
        data: {
            eventID: data.eventID,
            chapter: data.chapter,
            memberID: data.memberID,
            manageMode: manageMode,
            action: action,
            confirm: confirm
        },
        dataType: "json",
        beforeSend: function() {
            $(".chapter_menu_loader[data-chapter="+data.chapter+"]").show();
            $(".assignChapterLoader.dialog_f").show();
        }
    })
        .done(function(response) {
            if(response.success) {
                $(".chapter_members").hide();

                $('html, body').css({
                    'overflow': 'auto',
                    'height': 'auto'
                });

                // Update chapters block
                const chapterBlock = $(".chapter_"+data.chapter);
                const chapterMenu = $(".chapter_menu", chapterBlock);
                if(action === "add") {
                    $("button", chapterBlock).hide();
                    $(".manage_username", chapterBlock).show();
                    $(".manage_username .uname", chapterBlock).text(data.memberName);

                    chapterMenu.show();
                    const removeChapterMenu = $("li[data-id=remove_chapter]", chapterBlock);
                    removeChapterMenu.attr("data-userid", data.memberID);
                    removeChapterMenu.attr("data-username", data.memberName);
                    const moveBackMenu = $("li[data-id=move_back]", chapterBlock);
                    moveBackMenu.attr("data-userid", data.memberID);
                } else {
                    $("button", chapterBlock).show();
                    $(".manage_username", chapterBlock).hide();
                    $(".manage_username .uname", chapterBlock).text("");
                    chapterMenu.hide();
                }

                // Update members block
                const memberBlock = $(".manage_members .member_username[data="+data.memberID+"]").parent("li");
                let arr = $(".member_chapters span", memberBlock).text().split(", ");
                let currentChapNum = parseInt($(".member_username span", memberBlock).text());

                if(action === "add") {
                    if(arr[0] === "")
                        arr[0] = data.chapter;
                    else
                        arr.push(data.chapter);

                    arr.sort(function(a, b){return a-b});

                    currentChapNum++;
                } else {
                    arr = $.grep(arr, function( a ) {
                        return a !== data.chapter;
                    });
                    currentChapNum--;
                }

                $(".member_username span", memberBlock).text(currentChapNum);
                $(".assign_chapter[data="+data.memberID+"]").prev(".member_username").children(".divvalue").children("span").text(currentChapNum);

                if(arr.length > 0) {
                    $(".member_chapters span", memberBlock).html("<b>"+arr.join("</b>, <b>")+"</b>");
                    $(".member_chapters", memberBlock).show();
                } else {
                    $(".member_chapters span", memberBlock).html("");
                    $(".member_chapters", memberBlock).hide();
                }

                if (action === "add") {
                    sendUserEmail(data.eventID, data.memberID, data.chapter);
                }
            } else {
                if (typeof response.confirm !== "undefined") {
                    renderConfirmPopup(Language.deleteChapterConfirmTitle, response.message, function() {
                        $( this ).dialog( "close" );
                        assignChapter(data, "delete", response.confirm);
                    });
                } else if (typeof response.error !== "undefined") {
                    renderPopup(response.error);
                }
            }
        })
        .always(function() {
            $(".chapter_menu_loader[data-chapter="+data.chapter+"]").hide();
            $(".assignChapterLoader.dialog_f").hide();
        });
}

function sendUserEmail(eventID, memberID, chapter) {
    $.post("/events/rpc/send_user_email", {
        eventID: eventID,
        chapter: chapter,
        memberID: memberID
    });
}