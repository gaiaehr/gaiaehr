<?php

$text = <<<EOF
/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.account.VoucherLine', {
    extend: 'Ext.data.Model',
	table: 'accvoucherline',
    fields: [
        {name: 'id',                    type: 'int'},
        {name: 'voucherId',             type: 'int', comment: 'Voucher'},
        {name: 'accountId',             type: 'int', comment: 'Account'},
	    {name: 'moveLineId',            type: 'int', comment: 'Journal Item'},
//      {name: 'companyId',             type: 'int', comment:'Company (Not Used)'},
//      {name: 'accountAnalyticId',     type: 'int', comment:'Analytic Account (Not Used)'},

	    {name: 'reconcile',             type: 'bool', defaultValue: false, comment: 'Full Reconcile'},

	    {name: 'code',                  type: 'string', comment: 'COPAY/CPT/HCPCS/SKU codes'},
        {name: 'name',                  type: 'string', comment: 'Description'},
	    {name: 'type',                  type: 'string', comment: 'debit/credit'},

	    {name: 'amountUnreconciled',    type: 'float', comment: 'Open Balance'},
	    {name: 'amountUntax',           type: 'float', comment: 'Untax Amount'},
	    {name: 'amountOriginal',        type: 'float', comment: 'Default Amount'},
	    {name: 'amount',                type: 'float', comment: 'Amount'}
    ],/**
 GaiaEHR (Electronic Health Records)
 User.js
 User Model
 Copyright (C) 2012 Certun, inc.

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

Ext.define( 'App.model.administration.tmpUser',
{
	extend : 'Ext.data.Model',
	table: 'tmpusers',
	fields : [
	{
		name : 'id',
		type : 'int',
		dataType : 'bigint',
		len: 20,
		primaryKey : true,
		autoIncrement : true,
		allowNull : false,
		store: true
	},
	{
		name : 'username',
		type : 'string',
		dataType : 'varchar',
		len : 25,
		allowNull : false,
		store: true
	},
	{
		name : 'password',
		type : 'string',
		dataType : 'blob',
		allowNull : false,
		store: true
	},
	{
		name : 'authorized',
		type : 'bool',
		dataType : 'tinyint',
		len : 1,
		defaultValue : 0,
		store: true
	},
	{
		name : 'active',
		type : 'bool',
		dataType : 'tinyint',
		len : 1,
		store: true
	},
	{
		name : 'info',
		type : 'string',
		dataType: 'longtext',
		store: true
	},
	{
		name : 'source',
		type : 'int',
		dataType : 'tinyint',
		len: 4,
		store: true
	},
	{
		name : 'fname',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'mname',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'lname',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'fullname',
		type : 'string',
		dataType : 'varchar',
		store: false
	},
	{
		name : 'federaltaxid',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'federaldrugid',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'upin',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'facility',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'facility_id',
		type : 'int',
		dataType : 'int',
		len: 11,
		store: true
	},
	{
		name : 'see_auth',
		type : 'bool',
		dataType : 'tinyint',
		len: 1,
		store: true
	},
	{
		name : 'npi',
		type : 'string',
		dataType : 'varchar',
		len: 15,
		store: true
	},
	{
		name : 'title',
		type : 'string',
		dataType : 'varchar',
		len: 30,
		store: true
	},
	{
		name : 'specialty',
		type : 'string',
		dataType : 'varchar',
		len: 255,
		store: true
	},
	{
		name : 'cal_ui',
		type : 'string',
		dataType : 'tinyint',
		len: 4,
		store: true
	},
	{
		name : 'taxonomy',
		type : 'string',
		dataType : 'varchar',
		len: 30,
		store: true
	},
	{
		name : 'calendar',
		type : 'bool',
		dataType : 'tinyint',
		len: 1,
		store: true
	},
	{
		name : 'abook_type',
		type : 'string',
		dataType : 'varchar',
		len: 31,
		store: true
	},
	{
		name : 'pwd_expiration_date',
		type : 'string',
		dataType : 'longtext',
		store: true
	},
	{
		name : 'pwd_history1',
		type : 'string',
		dataType : 'longtext',
		store: true
	},
	{
		name : 'pwd_history2',
		type : 'string',
		dataType : 'longtext',
		store: true
	},
	{
		name : 'default_warehouse',
		type : 'string',
		dataType : 'varchar',
		len: 31,
		store: true
	},
	{
		name : 'role_id',
		type : 'int',
		dataType : 'int',
		store: false
	}]
} );

EOF;


$text =  preg_replace("(((/\*(.|\n)*\*/|//(.*))|Ext.define(.*) *|\);)|(\"| |)proxy(.|\n)*},)", '', $text); //clean coments
print '1) '.$text;
print '<br>';
print '<br>';
$text =  preg_replace("/(,|{|\t|\n|\r|  )( |)(\w*):/", "$1$2\"$3\":", $text);
print '2) '.$text;
print '<br>';
print '<br>';
$text =  preg_replace("/([0-9]+\.[0-9]+)/", "\"$1\"", $text);
print '3) '.$text;
print '<br>';
print '<br>';
$text =  preg_replace("(')", '"', $text);
print '4) '.$text;
print '<pre>';
print_r(json_decode($text, true));