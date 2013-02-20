/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 2/18/12
 * Time: 11:09 PM
 */
Ext.define('App.model.patient.EncounterCPTsICDs', {
	extend : 'Ext.data.Model',
	table: {
		name:'encountercptsicds',
		engine:'InnoDB',
		autoIncrement:1,
		charset:'utf8',
		collate:'utf8_bin',
		comment:'Encounter CPTs and ICDs Data'
	},
	fields : [
		{ name: 'id', type:'string' },
		{ name: 'pid', type:'int' },
		{ name: 'eid', type:'int' },
		{ name: 'code', type:'string' },
		{ name: 'code_text_medium', type:'string' },
		{ name: 'dx_pointers', type:'string' },
		{ name: 'dx_children'}
	],
	proxy: {
		type: 'direct',
		api: {
			read: Encounter.getEncounterCptDxTree,
			create: Encounter.addEncounterCptDxTree,
//			update: Encounter.updateEncounterCptDxTree,
			destroy: Encounter.removeEncounterCptDxTree
		}
	}
});