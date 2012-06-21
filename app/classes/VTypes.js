// *************************************************************************************
// Validation Object 
// Description:
// Code to validate different kind of fields
// ************************************************************************************* 
Ext.apply(Ext.form.VTypes, {

    // ---------------------------------------
    // Validate Empty fields, empty field not allowed
    // Less than 3 characters will be no good
    // ---------------------------------------
    empty_3chr : function(val, field) {
        return val.length > 2;
    }, empty_3chrText: 'This field must have more than 3 characters and must be not empty.',

    // ---------------------------------------
    // Validate Empty fields, empty field not allowed
    // Less than 7 characters will be no good
    // ---------------------------------------
    empty_7chr : function(val, field) {
        return val.length > 6;
    }, empty_7chrText: 'This field must have more than 7 characters and must be not empty.',

    // ---------------------------------------
    // Validate Empty fields, empty field not allowed
    // ---------------------------------------
    empty : function(val, field) {
        return val.length > 0;
    }, emptyText: 'This field must not be empty.',

    // ---------------------------------------
    // Validate Social Security Numbers fields, empty field not allowed
    // Less than 3 characters will be no good
    // ---------------------------------------
    SSN : function(val, field) {
        var matchArr = val.match(/^(\d{3})-?\d{2}-?\d{4}$/);
        var numDashes = val.split('-').length - 1;
        if (matchArr == null || numDashes == 1) {
            return false;
        } else return parseInt(matchArr[1], 10) != 0;
    }, SSNText: 'Social Security Numbers, must no be empty or in the wrong format. (555-55-5555).',

    // ---------------------------------------
    // Validate Day of Birth, empty field not allowed
    // YYYY-MM-DD
    // ---------------------------------------
    dateVal : function(val, field) {
        // String format yyyy-mm-dd
        var rgx = /^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/;
        return val.match(rgx);
    }, dateValText: 'Incorrect date format (YYYY-MM-DD).',

    // ---------------------------------------
    // Validate email, empty field not allowed
    // abc@abc.com
    // ---------------------------------------
    checkEmail : function(val, field){
        var rgx = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
        return val.match(rgx);
    }, checkEmailText: 'This field should be an email address in the format user@domain.com',

    // ---------------------------------------
    // Validate for an IP Address format
    // ---------------------------------------
    ipaddress : function( val, field){
        var rgx = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/i;
        return val.match(rgx);
    }, ipaddressText: 'This field should be an IP address in the format 192.168.0.1',

    // ---------------------------------------
    // Validate for an valid Phone Number
    // ---------------------------------------
    phoneNumber : function( val, field){
        var rgx = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
        return val.match(rgx);
    }, phoneNumberText: 'This field should be an valid PHONE number, in the format (000)-000-0000',

    // ---------------------------------------
    // Validate for an valid Canadian & US
    // Postal Code
    // ---------------------------------------
    postalCode : function( val, field){
        var regexObj = {
            canada 		: /^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i , //i for case-insensitive
            usa    		: /^\d{5}(-\d{4})?$/
        };
        // check for canada at first
        if( val.match(regexObj.canada) ) {
            return true;
        } else {
            // now check for USA
            regexp = null;
            return val.match(regexObj.usa);
        }
    }, postalCodeText: 'This field should be an valid Postal Code number, it can by Canadian or US',

    // ---------------------------------------
    // Validate for a valid new password
    // This is the re-type password vtype
    // ---------------------------------------
    password: function(val, field) {
        if (field.initialPassField) {
            var pwd = field.up('form').down('#' + field.initialPassField);
            return (val == pwd.getValue());
        }
        return true;
    }, passwordText: 'Passwords do not match',
    
    // ---------------------------------------
    // Validate for a correct MySQL field
    // compliance. 
    // NO SPACES, NO INVALID CHARACTERS
    // --------------------------------------- 
    mysqlField: function(val, field){
    	var regexObj = /[A-Za-z][A-Za-z0-9_]*/;
    	return val.match(regexObj);
    }, mysqlFieldText: 'The field entered has invalid characters'
    
});