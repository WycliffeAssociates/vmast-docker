/**
 * Created by Maxim on 29 Mar 2016.
 */

/***************************************************
 ** Class Event
 ***************************************************/
var Event = function() {
    var eventID;
    var projectID;
    var checkPairs;
    var sockets;

    // Define which variables and methods can be accessed
    return {
        eventID: eventID,
        projectID: projectID,
        checkPairs: [],
        sockets: [],
    }
};

// Export the Event class so you can use it in
// other files by using require("Event").Event
exports.Event = Event;
