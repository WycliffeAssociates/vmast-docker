/**************************************************

File name: Member.js 

Description: Class Member creation
Created By: Max Murzamuratov
Creation Date: 01-02-2016
Updated Date: 01-02-2016

Classes: Member - An object to hold member info

Functions: __construct() - create new Member object

Variables: Public id - contains the member id

***************************************************/

/***************************************************
** Class Member
***************************************************/
var Member = function() {
	var memberID;
	var userName;
	var firstName;
	var lastName;
	var isAdmin;
	var isSuperAdmin;
	var userType;
	var authToken;
	var events;

	// Define which variables and methods can be accessed
	return {
		memberID: memberID,
		userName: userName,
		firstName: firstName,
		lastName: lastName,
		isAdmin: isAdmin,
        isSuperAdmin: isSuperAdmin,
		userType: userType,
		authToken: authToken,
		events: [],
	}
};

// Export the Member class so you can use it in
// other files by using require("Member").Member
exports.Member = Member;