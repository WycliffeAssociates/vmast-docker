let socket, sctUrl = socketUrl;

$(document).ready(function () {
    socket = io.connect(sctUrl, { transports: ["websocket"] });

    socket.on('connect', OnConnected);
    socket.on('reconnect', OnConnected);
    socket.on('chat message', OnChatMessage);
    socket.on('event room update', OnEventRoomUpdate);
    socket.on('project room update', OnProjectRoomUpdate);
    socket.on('system message', OnSystemMessage);
    socket.on('checking request', OnCheckingRequest);

    socket.on("connect_error", (err) => {
        console.log(`connect_error due to ${err.message}`);
    });

    $("#chat_container").chat({
        step: step,
        memberID: memberID,
        eventID: eventID,
        chkMemberID: chkMemberID,
        disableChat: disableChat,
        isAdmin: isAdmin,
        onSendMessage: function()
        {
            socket.emit('chat message', this);
        }
    });
});

function OnConnected()
{
    $("#chat_container").chat("clearMessages");

    var data = {
        memberID: memberID,
        eventID: eventID,
        projectID: projectID,
        aT: aT,
        step: step,
        chkMemberID: chkMemberID,
    };
    this.emit("new member", data);
}

function OnChatMessage(data)
{
    $("#chat_container").chat("newMessageArrived", data);
}

function OnEventRoomUpdate(roomMates)
{
    var membersObj = $(".member_item");
    if(membersObj.length > 0)
    {
        $(".online_indicator", membersObj).removeClass("online");
        $(".online_status", membersObj).hide();
        $(".offline_status", membersObj).show();
    }

    for(var rm in roomMates)
    {
        var memberObj = $(".member_item[data="+roomMates[rm].memberID+"]");
        if(memberObj.length > 0)
        {
            $(".online_indicator", memberObj).addClass("online");
            $(".online_status", memberObj).show();
            $(".offline_status", memberObj).hide();
        }
    }

    $("#chat_container").chat("updateChatMembers", roomMates);
}

function OnProjectRoomUpdate(roomMates)
{
}

function OnSystemMessage(data)
{
    let msg = "";
    switch (data.type)
    {
        case "logout":
            window.location = "/members/logout";
            break;

        case "memberConnected":
            const t_mode = typeof tMode != "undefined" ? tMode : "";
            const msgData = {
                eventID: eventID,
                step: step,
                chkMemberID: chkMemberID,
                isChecker: isChecker,
                tMode: t_mode
            };
            this.emit('step enter', msgData);
            break;

        case "peerEnter":
            msg = Language.partnerJoinedPeerEdit;
        case "checkEnter":
            if(chkMemberID == 0 || $(".checker_waits").length > 0) {
                chkMemberID = parseInt(data.memberID);
                $(".check_request").remove();
                $(".checker_name_span").text(data.userName);
                $(".chk_title").text(data.userName);

                socket.io.reconnect();
                
				if(step != "") {
                    msg = Language.checkerJoined;
                    $(".alert.alert-danger, .alert.alert-success").remove();
                    renderPopup(msg);
                }

                $("#chat_container").chat("options", {chkMemberID: chkMemberID});
                $("#chat_container").chat("update");
            }
            break;

        case "prvtMsgs":
            $("#chat_container").chat("updatePrivateMessages", data);
            break;

        case "evntMsgs":
            $("#chat_container").chat("updateEventMessages", data);
            break;

        case "projMsgs":
            $("#chat_container").chat("updateProjectMessages", data);
            break;

        case "checkDone":
            if(typeof isChecker != "undefined" && isChecker) return;
            if(typeof isInfoPage != "undefined") return;

            $(".alert.alert-danger, .alert.alert-success").remove();
            renderPopup(Language.checkerApproved);
            break;

        case "comment":
            if(data.memberID == memberID) return;

            const editor = $(".editComment[data-chunk='"+data.verse+"']");
            data.text = unEscapeStr(data.text);

            if(editor.length === 1) {
                const numText = editor.prev(".comments_number").text();
                let num = numText.trim() !== "" ? parseInt(numText) : 0;

                if (data.deleted) {
                    $(".comment[data-id="+data.cID+"]").remove();
                    num--;
                } else {
                    const levelMarker = $("<span />")
                        .addClass("mdi mdi-numeric-"+data.level+"-box-multiple-outline")
                        .prop("title", "Level " + data.level);
                    const name = $("<b />").text(data.name + ": ")

                    const comment = $("<div />").addClass("comment")
                        .attr("data-id", data.cID)
                        .append(levelMarker)
                        .append(" ")
                        .append(name)
                        .append(data.text);

                    editor.next(".comments").prepend(comment.prop('outerHTML'));
                    $(".comments_list[data-chunk='"+data.verse+"']").prepend(comment.prop('outerHTML'))

                    num++;
                    if(num === 1) editor.prev(".comments_number").addClass("hasComment");
                }
                num = num > 0 ? num : "";
                editor.prev(".comments_number").text(num);
                if(num <= 0) editor.prev(".comments_number").removeClass("hasComment");
            }
            break;

        case "keyword":
            if(typeof isInfoPage == "undefined" &&
                (typeof step != "undefined"
                    && (step == EventSteps.KEYWORD_CHECK || EventCheckSteps.PEER_REVIEW_L2)))
                highlightKeyword(data.verseID, data.text, data.index, data.remove == "true");
            break;

        case "chkStarted":
            const notif = $("a.notifa[data-anchor='" + data.id + "']");
            if(notif.length > 0) {
                notif.remove();
                let count = parseInt($(".notif_count").text());
                count--;
                $(".notif_count").text(Math.max(count, 0));

                if(count <= 0) {
                    $(".notif_count").remove();
                    $(".notif_block").html('<div class="no_notif">'+Language.noNotifsMsg+'</div>');
                }
            }
            break;
    }
}

function OnCheckingRequest(data) {
    if($.inArray(memberID.toString(), data.excludes) >= 0)
        return false;

    if (typeof data.to != 'undefined' && data.to !== memberID.toString()) {
        return false;
    }

    $.ajax({
        url: "/events/rpc/get_notifications",
        method: "post",
        dataType: "json",
    })
    .done(function(data) {
        if(data.success) {
            if(data.notifications.length > 0) {
                $(".notif_block .no_notif").remove();
                $(".notif_count").remove();
                $("#notifications").append('<span class="notif_count">'+data.notifications.length+'</span>');
                let notifications = data.notifications.join("");
                $(".notif_block").html(notifications);
                const notificationSound = document.getElementById('notif');
                notificationSound.play();
            } else {
                $(".notif_count").remove();
                $(".notif_block").html('<div class="no_notif">'+data.no_notifications+'</div>');
            }
        }
    });
}