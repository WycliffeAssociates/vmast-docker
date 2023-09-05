/*
https = require('https'),
    server = http.createServer({
        key:    fs.readFileSync(process.env.SSL_KEY),
        cert:   fs.readFileSync(process.env.SSL_CRT),
        ca:     fs.readFileSync(process.env.SSL_CA)
    }, app).listen(process.env.SOCKET_PORT),
 */

const app = require('express')(),
    fs = require('fs'),
    http = require('http'),
    server = http.createServer(app).listen(process.env.SOCKET_PORT),
    io = require('socket.io')(server),
    redis = require("redis"),
    util = require("util"),
    _ = require("underscore"),
    XMLHttpRequest = require("xmlhttprequest-ssl").XMLHttpRequest,
    Member = require("./Member").Member,
    Event = require("./Event").Event,
    EventSteps = require("./EventSteps").EventSteps;

const members = [];

const clientRedis = redis.createClient({
    host: process.env.REDIS_HOST,
    port: process.env.REDIS_PORT
});

clientRedis.on("connect", function() {
    clientRedis.select(process.env.REDIS_DB, function() {
        inspect(`Redis database ${process.env.REDIS_DB} selected`);
    });
});

io.on('connection', function(socket) {
    //inspect('a user connected: %s', socket.id);

    socket.on('disconnect', function() {
        //inspect('user disconnected: %s', this.id);

        const member = getMemberBySocketId(this.id);

        if(member) {
            let sktNum = 0;

            for(const evnt in member.events) {
                const index = member.events[evnt].sockets.indexOf(this.id);

                if(index === -1) {
                    sktNum += member.events[evnt].sockets.length;
                    continue;
                }

                member.events[evnt].sockets.splice(index, 1);
                sktNum += member.events[evnt].sockets.length;

                this.leave("eventroom" + member.events[evnt].eventID);
                this.leave("projectroom" + member.events[evnt].projectID);

                // Delete event and project record if it is connected to no socket
                if(member.events[evnt].sockets.length === 0) {
                    const eventID = member.events[evnt].eventID;
                    const projectID = member.events[evnt].projectID;
                    delete member.events[evnt];

                    const eventRoomMates = getMembersByRoomID(eventID, "event");
                    io.to("eventroom" + eventID).emit('event room update', eventRoomMates);

                    const projectRoomMates = getMembersByRoomID(projectID, "project");
                    io.to("projectroom" + projectID).emit('project room update', projectRoomMates);
                }
            }

            // Delete member if he is connected to no socket
            if(sktNum === 0)
                delete members["user" + member.memberID];
        }
    });

    socket.on('new member', function(data) {
        const sct = this;
        const member = getMemberByUserId("user" + data.memberID);

        if(member && member.authToken === data.aT) {
            const event = getMemberEvent(member, data.eventID);

            if(!_.isEmpty(event)) {
                event.sockets.push(sct.id);
                sct.join("eventroom" + event.eventID);
                sct.join("projectroom" + event.projectID);

                const eventRoomMates = getMembersByRoomID(event.eventID, "event");
                io.to("eventroom" + event.eventID).emit('event room update', eventRoomMates);

                const projectRoomMates = getMembersByRoomID(event.projectID, "project");
                io.to("projectroom" + event.projectID).emit('project room update', projectRoomMates);

                // Add pair to checkers chat
                let checkPair = "";

                if(data.chkMemberID > 0) {
                    checkPair = "pair-"
                        + event.eventID
                        + "-"
                        + (Math.min(data.chkMemberID, member.memberID))
                        + "-"
                        + (Math.max(data.chkMemberID, member.memberID));

                    if(event.checkPairs.indexOf(checkPair) < 0)
                        event.checkPairs.push(checkPair);
                } else {
                    checkPair = "zero";
                }

                sendSavedMessages(sct, event, checkPair);
            } else {
                registerNewMemberEvent(data, sct, member);
            }

            return;
        }

        registerNewMemberEvent(data, sct, {});
    });

    socket.on('chat message', function(chatData)
    {
        if(chatData.msg.trim() === "")
            return false;

        const member = getMemberBySocketId(this.id);

        let id, pairID;
        if (member) {
            let msgObj, date;
            const client = {
                memberID: member.memberID,
                userName: member.userName,
                fullName: member.firstName + " " + member.lastName.charAt(0) + "."
            };
            const event = getMemberEvent(member, chatData.eventID);

            if (!_.isEmpty(event)) {
                date = Date.now();
                msgObj = {
                    member: client,
                    msg: _.escape(chatData.msg),
                    date: date,
                    chatType: "chk"
                };

                if (chatData.chatType === "chk") {
                    id = chatData.chkMemberID;

                    if (id <= 0) return false;

                    pairID = "pair-"
                        + event.eventID
                        + "-"
                        + (Math.min(id, member.memberID))
                        + "-"
                        + (Math.max(id, member.memberID));

                    if (event.checkPairs.indexOf(pairID) < 0)
                        return false;

                    const translator = getMemberByUserId("user" + id);

                    if (typeof translator != 'undefined') {
                        const trEvent = getMemberEvent(translator, event.eventID);

                        if (!_.isEmpty(trEvent)) {
                            // Send message to co-translator/checker
                            for (const skt in trEvent.sockets) {
                                io.to(trEvent.sockets[skt]).emit('chat message', msgObj);
                            }
                        }
                    }

                    // Send message to sender himself
                    for (const skt in event.sockets) {
                        io.to(event.sockets[skt]).emit('chat message', msgObj);
                    }

                    clientRedis.ZADD("rooms:" + pairID, date, JSON.stringify(msgObj));
                } else if (chatData.chatType === "evnt") {
                    msgObj.chatType = "evnt";
                    io.to("eventroom" + event.eventID).emit('chat message', msgObj);

                    clientRedis.ZADD("rooms:event-" + event.eventID, date, JSON.stringify(msgObj));
                } else if (chatData.chatType === "proj") {
                    msgObj.chatType = "proj";
                    io.to("projectroom" + event.projectID).emit('chat message', msgObj);

                    clientRedis.ZADD("rooms:project-" + event.projectID, date, JSON.stringify(msgObj));
                }
            }
        }
    });

    socket.on('system message', function(data) {
        const member = getMemberBySocketId(this.id);

        if(member) {
            const event = getMemberEvent(member, data.eventID);

            if(!_.isEmpty(event)) {
                let checkMember, checkEvent;
                switch (data.type) {
                    case "checkDone":
                        checkMember = getMemberByUserId("user" + data.chkMemberID);
                        if(typeof checkMember !== 'undefined') {
                            checkEvent = getMemberEvent(checkMember, event.eventID);

                            if(!_.isEmpty(checkEvent)) {
                                // Send message to check member
                                for(const skt in checkEvent.sockets) {
                                    const name = member.firstName + " " + member.lastName.charAt(0) + ".";
                                    io.to(checkEvent.sockets[skt]).emit('system message', {type: "checkDone", name: name});
                                }
                            }
                        }
                        break;

                    case "comment":
                        const commentData = {
                            type: "comment",
                            memberID: member.memberID,
                            name: member.firstName + " " + member.lastName.charAt(0) + ".",
                            verse: _.escape(data.verse || ""),
                            text: _.escape(data.text || ""),
                            level: _.escape(data.level || 1),
                            cID: _.escape(data.cID || 0),
                            deleted: data.deleted === true
                        };

                        io.to("eventroom" + event.eventID).emit('system message', commentData);
                        break;

                    case "keyword":
                        checkMember = getMemberByUserId("user" + data.chkMemberID);

                        if(typeof checkMember != 'undefined') {
                            checkEvent = getMemberEvent(checkMember, event.eventID);

                            if(!_.isEmpty(checkEvent)) {
                                const keywordData = {
                                    type: "keyword",
                                    remove: _.escape(data.remove),
                                    verseID: _.escape(data.verseID),
                                    index: _.escape(data.index),
                                    text: _.escape(data.text)
                                };

                                // Send message to check member
                                for(const skt in checkEvent.sockets) {
                                    io.to(checkEvent.sockets[skt]).emit('system message', keywordData);
                                }
                            }
                        }
                        break;

                    case "notification":
                        const msgObj = {
                            excludes: [member.memberID],
                            to: data.toMemberID,
                            anchor: "check:" + event.eventID + ":" + member.memberID
                        };
                        io.to("eventroom" + event.eventID).emit('checking request', msgObj);
                        break;
                }
            }
        }
    });

    socket.on('step enter', function(data) {
        const member = getMemberBySocketId(this.id);
        const eSteps = new EventSteps();

        if(member) {
            const event = getMemberEvent(member, data.eventID);

            if(!_.isEmpty(event)) {
                switch (data.step) {
                    case eSteps.PEER_REVIEW:
                    case eSteps.KEYWORD_CHECK:
                    case eSteps.CONTENT_REVIEW:
                    case eSteps.PEER_REVIEW_L2:
                    case eSteps.PEER_REVIEW_L3:
                        if((data.step === eSteps.KEYWORD_CHECK || data.step === eSteps.CONTENT_REVIEW)
                            && typeof data.tMode != "undefined"
                            && ["tn","tq","tw","sun","obs","bc","bca"].indexOf(data.tMode) > -1) break;

                        let messageType = "checkEnter";
                        if (!data.isChecker) {
                            const msgObj = {
                                excludes: [member.memberID],
                                anchor: "check:" + event.eventID + ":" + member.memberID
                            };
                            io.to("eventroom" + event.eventID).emit('checking request', msgObj);
                        } else {
                            if (data.chkMemberID > 0) {
                                const checkMember = getMemberByUserId("user" + data.chkMemberID);

                                if (typeof checkMember != 'undefined') {
                                    const checkEvent = getMemberEvent(checkMember, event.eventID);

                                    if (!_.isEmpty(checkEvent)) {
                                        let checkPair = "pair-"
                                            + checkEvent.eventID
                                            + "-"
                                            + (Math.min(data.chkMemberID, member.memberID))
                                            + "-"
                                            + (Math.max(data.chkMemberID, member.memberID));

                                        if (checkEvent.checkPairs.indexOf(checkPair) < 0)
                                            checkEvent.checkPairs.push(checkPair);

                                        // Send message to check member
                                        for (const skt in checkEvent.sockets) {
                                            const name = member.firstName + " " + member.lastName.charAt(0) + ".";
                                            io.to(checkEvent.sockets[skt]).emit('system message', {
                                                type: messageType,
                                                memberID: member.memberID,
                                                userName: name
                                            });
                                        }

                                        // Send message to roommates
                                        io.to("eventroom" + event.eventID).emit('system message', {
                                            type: "chkStarted",
                                            id: "check:" + event.eventID + ":" + data.chkMemberID
                                        });
                                    }
                                }
                            }
                        }
                        break;
                }
            }
        }
    });

    socket.on('videoCallMessage', function (data) {
        const member = getMemberBySocketId(this.id);

        if(member) {
            const event = getMemberEvent(member, data.eventID);

            if(!_.isEmpty(event)) {
                const id = data.chkMemberID;

                if(id <= 0) return false;

                const pairID = "pair-"
                    + event.eventID
                    + "-"
                    + (Math.min(id, member.memberID))
                    + "-"
                    + (Math.max(id, member.memberID));

                if(event.checkPairs.indexOf(pairID) < 0)
                    return false;

                const translator = getMemberByUserId("user" + id);

                if(typeof translator != 'undefined') {
                    const trEvent = getMemberEvent(translator, event.eventID);

                    if(!_.isEmpty(trEvent)) {
                        if(data.type === "calling") {
                            data.userName = member.userName;
                            data.memberID = member.memberID;
                        }

                        // Send message to co-translator/checker
                        for(const skt in trEvent.sockets) {
                            io.to(trEvent.sockets[skt]).emit('videoCallMessage', data);
                        }
                    }
                }

                // Send message back to member sockets
                if(data.type === "calling") {
                    for(const skt in event.sockets) {
                        io.to(event.sockets[skt]).emit('callAnswered', {});
                    }
                }
            }
        }
    });
});

/**************************************************
 ** HELPER FUNCTIONS
 **************************************************/
function registerNewMemberEvent(data, sct, member) {
    const xhr = new XMLHttpRequest({
        pfx: null,
    });

    xhr.onreadystatechange = function() {
        if (this.readyState === this.DONE) {
            try {
                const response = JSON.parse(this.responseText);

                if(!_.isEmpty(response)) {
                    const newEvent = new Event();
                    newEvent.eventID = data.eventID;
                    newEvent.projectID = data.projectID;
                    newEvent.sockets.push(sct.id);

                    // Add pair to checkers chat
                    let checkPair;

                    if(data.chkMemberID > 0) {
                        checkPair = "pair-"
                            + newEvent.eventID
                            + "-"
                            + (Math.min(data.chkMemberID, response.memberID))
                            + "-"
                            + (Math.max(data.chkMemberID, response.memberID));

                        newEvent.checkPairs.push(checkPair);
                    } else {
                        checkPair = "zero";
                    }

                    if(_.isEmpty(member)) {
                        const newMember = new Member();
                        newMember.memberID = response.memberID;
                        newMember.userName = response.userName;
                        newMember.firstName = response.firstName;
                        newMember.lastName = response.lastName;
                        newMember.isAdmin = response.isAdmin;
                        newMember.isSuperAdmin = response.isSuperAdmin;
                        newMember.authToken = data.aT;
                        newMember.events.push(newEvent);

                        members["user" + newMember.memberID] = newMember;
						//inspect("------------- @"+newMember.userName);
                    } else {
                        member.events.push(newEvent);
                        //inspect("------------- @"+member.userName);
                    }

                    sct.join("eventroom" + data.eventID);
                    sct.join("projectroom" + data.projectID);

                    const eventRoomMates = getMembersByRoomID(data.eventID, "event");
                    io.to("eventroom" + data.eventID).emit('event room update', eventRoomMates);

                    const projectRoomMates = getMembersByRoomID(data.projectID, "project");
                    io.to("projectroom" + data.projectID).emit('project room update', projectRoomMates);

                    sendSavedMessages(sct, newEvent, checkPair);
                } else {
                    sct.emit('system message', {type: "logout"});
                }
            } catch(err) {
                //inspect(err);
            }
        }
    };

    xhr.open("GET", `http://web/members/rpc/auth/${data.memberID}/${data.eventID}/${data.aT}`);
    xhr.send();
}


/**
 * Find member by userID
 * @param userID
 * @returns {*}
 */
function getMemberByUserId(userID) {
    if(_.keys(members).length > 0) {
        return members[userID];
    }

    return false;
}


/**
 * Find member by socketID
 * @param socketID
 * @returns {*}
 */
function getMemberBySocketId(socketID) {
    if(_.keys(members).length <= 0)
        return null;

    for (const m in members) {
        for (const e in members[m].events) {
            if(members[m].events[e].sockets.indexOf(socketID) > -1) {
                return members[m];
            }
        }
    }

    return null;
}

function getMembersByRoomID(roomID, room) {
    const roomMates = [];

    if(_.keys(members).length <= 0)
        return roomMates;

    for (const m in members) {
        if(_.keys(members[m].events).length <= 0)
            continue;

        for(const e in members[m].events) {
            let mRoomID = 0;
            switch (room) {
                case "event":
                    mRoomID = members[m].events[e].eventID;
                    break;
                case "project":
                    mRoomID = members[m].events[e].projectID;
                    break;
                default:
                    break;
            }

            if(mRoomID === roomID) {
                const member = {};
                member.memberID = members[m].memberID;
                member.userName = members[m].userName;
                member.isAdmin = members[m].isAdmin;
                member.isSuperAdmin = members[m].isSuperAdmin;
                member.name = members[m].firstName + " " + members[m].lastName.charAt(0) + ".";

                roomMates.push(member);
            }
        }
    }

    return roomMates;
}

function getMemberEvent(member, eventID) {
    if(!_.isEmpty(member)) {
        for (const event in member.events) {
            if(member.events[event].eventID === eventID)
                return member.events[event];
        }
    }

    return {};
}

function sendSavedMessages(socket, event, checkPair) {
    const since = Date.now() - 60 * 24 * 60 * 60 * 1000; // get messages within 60 days period

    if(checkPair !== "zero") {
        clientRedis.ZREMRANGEBYSCORE("rooms:" + checkPair, "-inf", since);
        clientRedis.ZRANGEBYSCORE("rooms:" + checkPair, since, "+inf", "WITHSCORES", function(err, value) {
            try {
                if(!_.isEmpty(value)) {
                    socket.emit('system message', {type: "prvtMsgs", msgs: value});
                }
            } catch (err) {
                //inspect(err);
            }
        });
    }

    clientRedis.ZREMRANGEBYSCORE("rooms:event-" + event.eventID, "-inf", since);
    clientRedis.ZRANGEBYSCORE("rooms:event-" + event.eventID, since, "+inf", "WITHSCORES", function(err, value) {
        try {
            if(!_.isEmpty(value)) {
                socket.emit('system message', {type: "evntMsgs", msgs: value});
            }
        } catch (err) {
            //inspect(err);
        }
    });

    clientRedis.ZREMRANGEBYSCORE("rooms:project-" + event.projectID, "-inf", since);
    clientRedis.ZRANGEBYSCORE("rooms:project-" + event.projectID, since, "+inf", "WITHSCORES", function(err, value) {
        try {
            if(!_.isEmpty(value)) {
                socket.emit('system message', {type: "projMsgs", msgs: value});
            }
        } catch (err) {
            //inspect(err);
        }
    });

    socket.emit('system message', {type: "memberConnected"});
}

function inspect(obj, arg) {
    const _arg = arg || "";
    console.log(util.inspect(obj, { showHidden: true, depth: null }), _arg);
}
