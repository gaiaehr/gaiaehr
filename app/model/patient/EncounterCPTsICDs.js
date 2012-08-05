/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.EncounterCPTsICDs', {
	extend : 'Ext.data.Model',
	fields : [
		{name: 'code'},
		{name: 'code_text'},
		{name: 'type'},
		{name: 'code_text_short'}
	],
	proxy  : {
		type       : 'direct',
		api        : {
			read: Encounter.getEncounterCodes
		},
		reader     : {
			type: 'json',
			root: 'encounter'
		}
	}
});