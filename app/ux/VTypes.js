/*
 GaiaEHR (Electronic Health Records)
 VTypes.js
 UX
 Copyright (C) 2012 Ernesto Rodriguez

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
Ext.apply(Ext.form.VTypes,
{

	// ---------------------------------------
	// Validate Empty fields, empty field not allowed
	// Less than 3 characters will be no good
	// ---------------------------------------
	empty_3chr : function(val, field)
	{
		return val.length > 2;
	},
	empty_3chrText : i18n('vtype_empty_3chr'),

	// ---------------------------------------
	// Validate Empty fields, empty field not allowed
	// Less than 7 characters will be no good
	// ---------------------------------------
	empty_7chr : function(val, field)
	{
		return val.length > 6;
	},
	empty_7chrText : i18n('vtype_empty_7chr'),

	// ---------------------------------------
	// Validate Empty fields, empty field not allowed
	// ---------------------------------------
	empty : function(val, field)
	{
		return val.length > 0;
	},
	emptyText : 'This field must not be empty.',

	// ---------------------------------------
	// Validate Social Security Numbers fields, empty field not allowed
	// Less than 3 characters will be no good
	// ---------------------------------------
	SSN : function(val, field)
	{
		var matchArr = val.match(/^(\d{3})-?\d{2}-?\d{4}$/);
		var numDashes = val.split('-').length - 1;
		if (matchArr == null || numDashes == 1)
		{
			return false;
		}
		else
			return parseInt(matchArr[1], 10) != 0;
	},
	SSNText : i18n('vtype_ssn'),

	// ---------------------------------------
	// Validate Day of Birth, empty field not allowed
	// YYYY-MM-DD
	// ---------------------------------------
	dateVal : function(val, field)
	{
		// String format yyyy-mm-dd
		var rgx = /^[0-9]{4}\-(0[1-9]|1[012])\-(0[1-9]|[12][0-9]|3[01])/;
		return val.match(rgx);
	},
	dateValText : i18n('vtype_dateVal'),

	// ---------------------------------------
	// Validate email, empty field not allowed
	// abc@abc.com
	// ---------------------------------------
	checkEmail : function(val, field)
	{
		var rgx = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
		return val.match(rgx);
	},
	checkEmailText : i18n('vtype_checkEmail'),

	// ---------------------------------------
	// Validate for an IP Address format
	// ---------------------------------------
	ipaddress : function(val, field)
	{
		var rgx = /^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}$/i;
		return val.match(rgx);
	},
	ipaddressText : i18n('vtype_ipaddress'),

	// ---------------------------------------
	// Validate for an valid Phone Number
	// ---------------------------------------
	phoneNumber : function(val, field)
	{
		var rgx = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
		return val.match(rgx);
	},
	phoneNumberText : i18n('vtype_phoneNumber'),

	// ---------------------------------------
	// Validate for an valid Canadian & US
	// Postal Code
	// ---------------------------------------
	postalCode : function(val, field)
	{
		var regexObj =
		{
			canada : /^[ABCEGHJKLMNPRSTVXY]\d[ABCEGHJKLMNPRSTVWXYZ]( )?\d[ABCEGHJKLMNPRSTVWXYZ]\d$/i, //i for case-insensitive
			usa : /^\d{5}(-\d{4})?$/
		};
		// check for canada at first
		if (val.match(regexObj.canada))
		{
			return true;
		}
		else
		{
			// now check for USA
			regexp = null;
			return val.match(regexObj.usa);
		}
	},
	postalCodeText : i18n('vtype_postalCode'),

	// ---------------------------------------
	// Validate for a valid new password
	// This is the re-type password vtype
	// ---------------------------------------
	password : function(val, field)
	{
		if (field.initialPassField)
		{
			var pwd = field.up('form').down('#' + field.initialPassField);
			return (val == pwd.getValue());
		}
		return true;
	},
	passwordText : i18n('vtype_password'),

	// ---------------------------------------
	// Validate for a correct MySQL field
	// compliance.
	// NO SPACES, NO INVALID CHARACTERS
	// ---------------------------------------
	mysqlField : function(val, field)
	{
		var regexObj = /[A-Za-z][A-Za-z0-9_]*/;
		return val.match(regexObj);
	},
	mysqlFieldText : i18n('vtype_mysqlField')

}); 