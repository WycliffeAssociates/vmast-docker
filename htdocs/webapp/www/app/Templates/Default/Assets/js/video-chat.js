/**
 * Created by mXaln on 07.09.2016.
 */
'use strict';

let isInitiator = false;
let isStarted = false;
let localStream;
let remoteStream;
let pc;
let time;
let timeoutInterval,
    timeoutDelay = 30000, // Call busy timeout
    timeoutClose,
    timeoutCloseDelay = 5000; // timeout of closing video window with error
const constraints = {audio: true, video: false};
let calleeID = 0;
let calleeName = "";
let callAnswered = false;

$(".videoCallOpen").click(openVideoDialog);
$(".video-chat-close").click(closeVideoDialog);
$("#hangupButton").click(onHangupClick);
$("#answerButton").click(onAnswerClick);
$("#cameraButton").click(manageCamera);
$("#micButton").click(manageMic);

const iceConfig = {
    'iceServers': [
        {"urls": "stun:stun.l.google.com:19302"}
    ]
};

const localVideo = $("#localVideo")[0];
const remoteVideo = $('#remoteVideo')[0];
const callLog = $('#callLog');

const callin = document.getElementById('callin');
callin.loop = true;
const callout = document.getElementById('callout');
callout.loop = true;

$(document).ready(function () {
    socket.on('videoCallMessage', function(message) {
        console.log('Client received message:', message);
        onVideoCallMessage(message);
    });

    socket.on('callAnswered', function() {
        if(!isInitiator && !callAnswered) {
            closeVideoDialog(false);
        }
    });

    window.onbeforeunload = function() {
        sendMessage({
            type: 'bye',
            callType: $("#chat_type").val(),
            eventID: eventID,
            memberID: memberID,
            isChecker: isChecker,
            chkMemberID: chkMemberID
        });
    };

    $(".video_chat_container").draggable({
        snap: "window",
        cursor: "move",
        containment: "window",
        scroll: false,
    }).resizable({
        containment: "body",
        aspectRatio: true,
        maxHeight: 640,
        minHeight: 300,
        resize: function( event, ui ) {
            $(".video").css("min-height", ui.size.height - 40);
            $(".video").css("max-width", ui.size.width);
            $("#remoteVideo").attr("height", $(".video").height());
        }
    });
});


// ----------------------------- Functions --------------------------------- //
function sendMessage(message) {
    console.log('Client sending message: ', message);
    socket.emit('videoCallMessage', message);
}

function maybeStart() {
    console.log('>>>>>>> maybeStart() ', isStarted, localStream);
    if (!isStarted && typeof localStream != 'undefined') {
        console.log('>>>>>> creating peer connection');
        createPeerConnection();
        pc.addStream(localStream);
        isStarted = true;
    }
}

function createPeerConnection() {
    try {
        pc = new RTCPeerConnection(iceConfig);
        pc.onicecandidate = handleIceCandidate;
        pc.onnegotiationneeded = handleNegotiationNeeded;
        pc.onaddstream = handleRemoteStreamAdded;
        pc.onremovestream = handleRemoteStreamRemoved;
        console.log('Created RTCPeerConnection');
    } catch (e) {
        callLog.html('Failed to create PeerConnection, exception: ' + e.message);
        timeoutClose = setTimeout(function () {
            closeVideoDialog();
        }, timeoutCloseDelay);
    }
}

function doCall() {
    $("#hangupButton").prop("disabled", false);
    $("#answerButton").hide().prop("disabled", true);

    sendMessage({
        type: 'calling',
        callType: $("#chat_type").val(),
        eventID: eventID,
        chkMemberID: chkMemberID,
        isChecker: isChecker,
        isIncoming: isInitiator,
        isVideoCall: constraints.video
    });
}

function doAnswer() {
    console.log('Sending onAnswerClick to peer.');

    pc.createAnswer().then(
        setLocalAndSendMessage,
        onCreateSessionDescriptionError
    );
}

function setLocalAndSendMessage(description) {
    const msg = {
        type: description.type,
        callType: $("#chat_type").val(),
        eventID: eventID,
        chkMemberID: chkMemberID,
        description: description
    };

    pc.setLocalDescription(description);
    console.log('setLocalAndSendMessage sending message', msg);
    sendMessage(msg);
}

// ----------------------------- Handler functions ----------------------------- //
function openVideoDialog() {
    constraints.video = $(this).hasClass("videocall");
    timeoutInterval = setTimeoutInterval();

    $(".video_chat_container").show();
    isInitiator = true;

    $("#answerButton").hide();
    $("#cameraButton").addClass("btn-success").removeClass("btn-danger").hide();
    $("#micButton").addClass("btn-success glyphicon-volume-up").removeClass("btn-danger glyphicon-volume-off").hide();
    $(".videoCallOpen").prop("disabled", true);

    callLog.html(Language.calling);
    callout.currentTime = 0;
    callout.play();

    navigator.mediaDevices.getUserMedia(constraints)
        .then(gotStream)
        .catch(function(e) {
            callout.pause();
            callLog.html(e.name + ": "+ e.message);
            timeoutClose = setTimeout(function () {
                closeVideoDialog();
            }, timeoutCloseDelay);
        });
}

function closeVideoDialog(bye) {
    bye = typeof bye != "undefined" ? bye : true;

    if(!$(".video_chat_container").is(":visible")) return;

    $(".video_chat_container").hide();
    $(".videoCallOpen").prop("disabled", false);

    callLog.html("");
    isStarted = false;
    isInitiator = false;
    callAnswered = false;
    calleeID = 0;
    calleeName = "";

    clearInterval(timeoutInterval);
    clearTimeout(timeoutClose);

    callout.pause();
    callin.pause();

    if(typeof pc != "undefined" && pc != null) {
        pc.close();
        pc = null;
    }

    if(typeof localStream != "undefined" && localStream) {
        if(localStream.getVideoTracks().length > 0)
            localStream.getVideoTracks()[0].stop();
        if(localStream.getAudioTracks().length > 0)
            localStream.getAudioTracks()[0].stop();

        localStream = null;
        localVideo.srcObject = null;
    }

    if(typeof remoteStream != "undefined" && remoteStream) {
        if(remoteStream.getVideoTracks().length > 0)
            remoteStream.getVideoTracks()[0].stop();
        if(remoteStream.getAudioTracks().length > 0)
            remoteStream.getAudioTracks()[0].stop();

        remoteStream = null;
        remoteVideo.srcObject = null;
    }

    if(bye) {
        sendMessage({
            type: 'bye',
            callType: $("#chat_type").val(),
            eventID: eventID,
            memberID: memberID,
            isChecker: isChecker,
            chkMemberID: chkMemberID
        });
    }
}

function onAnswerClick() {
    navigator.mediaDevices.getUserMedia(constraints)
        .then(gotStream)
        .catch(function(e) {
            callin.pause();
            callLog.html(e.name + ": "+ e.message);
            timeoutClose = setTimeout(function () {
                closeVideoDialog();
            }, timeoutCloseDelay);
        });
}


function onHangupClick() {
    console.log('Hanging up.');
    closeVideoDialog();
}

function manageCamera() {
    if(typeof localStream != "undefined") {
        if(localStream.getVideoTracks().length > 0) {
            const enabled = !localStream.getVideoTracks()[0].enabled;
            localStream.getVideoTracks()[0].enabled = enabled;
            if(enabled)
                $("#cameraButton").addClass("btn-success mdi-camcorder").removeClass("btn-danger mdi-camcorder-off");
            else
                $("#cameraButton").removeClass("btn-success mdi-camcorder").addClass("btn-danger mdi-camcorder-off");
        }
    }
}

function manageMic() {
    if(typeof localStream != "undefined") {
        if(localStream.getAudioTracks().length > 0) {
            const enabled = !localStream.getAudioTracks()[0].enabled;
            localStream.getAudioTracks()[0].enabled = enabled;
            if(enabled)
                $("#micButton").addClass("btn-success mdi-microphone").removeClass("btn-danger mdi-microphone-off");
            else
                $("#micButton").removeClass("btn-success mdi-microphone").addClass("btn-danger mdi-microphone-off");
        }

    }
}

function gotStream(stream) {
    console.log('Adding local stream.');

    if (localStream) return;

    localVideo.srcObject = stream;
    localStream = stream;

    if(!isInitiator) {
        callAnswered = true;

        sendMessage({
            type: 'calling',
            callType: $("#chat_type").val(),
            eventID: eventID,
            chkMemberID: chkMemberID,
            isChecker: isChecker,
            isIncoming: isInitiator
        });

        callin.pause();
        maybeStart();
    } else {
        calleeID = chkMemberID;
        doCall();
    }
}

function onVideoCallMessage(message) {
    // Do not accept call from checker if you are checker too
    if(message.isChecker && message.isChecker == isChecker) return false;

    switch(message.type) {
        case "calling":
            calleeID = message.memberID;
            calleeName = message.userName;

            if(message.isIncoming) { // Incoming call
                $(".video_chat_container").show();
                $(".videoCallOpen").prop("disabled", true);
                callLog.html(Language.incommingCall + " " + calleeName);
                $("#answerButton").show().prop("disabled", false);
                $("#hangupButton").prop("disabled", false);
                $("#cameraButton").addClass("btn-success").removeClass("btn-danger").hide();
                $("#micButton").addClass("btn-success glyphicon-volume-up").removeClass("btn-danger glyphicon-volume-off").hide();
                isInitiator = false;

                callin.currentTime = 0;
                callin.play();

                constraints.video = message.isVideoCall;
                timeoutInterval = setTimeoutInterval();
            } else { // Peer answered
                maybeStart();
            }
            break;

        case "offer":
            if(!isInitiator && !callAnswered) break;

            pc.setRemoteDescription(message.description);
            doAnswer();
            break;

        case "answer":
            if(!isInitiator && !callAnswered) break;

            if(isStarted) {
                pc.setRemoteDescription(message.description);
            }
            break;

        case "candidate":
            if(!isInitiator && !callAnswered) break;

            if(isStarted) {
                pc.addIceCandidate(message.candidate);
            }
            break;

        case "bye":
            if(calleeID == message.memberID)
                closeVideoDialog();
            break;
    }
}

function handleIceCandidate(event) {
    console.log('icecandidate event: ', event);
    if (event.candidate) {
        sendMessage({
            type: 'candidate',
            candidate: event.candidate,
            callType: $("#chat_type").val(),
            eventID: eventID,
            isChecker: isChecker,
            chkMemberID: chkMemberID
        });
    } else {
        console.log('End of candidates.');
    }
}

function handleNegotiationNeeded() {
    pc.createOffer(setLocalAndSendMessage, handleCreateOfferError);
}

function handleRemoteStreamAdded(event) {
    console.log('Remote stream added.');

    if (remoteStream) return;

    remoteVideo.srcObject = event.stream;
    remoteStream = event.stream;

    $("#answerButton").hide().prop("disabled", true);
    if(constraints.video) $("#cameraButton").show();
    $("#micButton").show();
    callLog.html("");
    if(!constraints.video) callLog.html(Language.audioChatWith + " " + calleeName);

    callout.pause();
    clearInterval(timeoutInterval);
    clearTimeout(timeoutClose);
}

function handleRemoteStreamRemoved(event) {
    callout.pause();
    callLog.html('Remote stream removed. Event: ', event);
    timeoutClose = setTimeout(function () {
        closeVideoDialog();
    }, timeoutCloseDelay);
}

function handleCreateOfferError(event) {
    callout.pause();
    callLog.html('createOffer() error: ', event);
    timeoutClose = setTimeout(function () {
        closeVideoDialog();
    }, timeoutCloseDelay);
}

function onCreateSessionDescriptionError(error) {
    callout.pause();
    callLog.html('Failed to create session description: ' + error.toString());
    timeoutClose = setTimeout(function () {
        closeVideoDialog();
    }, timeoutCloseDelay);
}

function setTimeoutInterval() {
    time = new Date().getTime();
    return setInterval(function () {
        const now = new Date().getTime();
        if((now - time) > timeoutDelay) {
            callLog.html(Language.callTimeout);
            callout.pause();
            $("#hangupButton").prop("disabled", true);
            timeoutClose = setTimeout(function () {
                closeVideoDialog();
            }, timeoutCloseDelay);
        }
    }, 1000);
}