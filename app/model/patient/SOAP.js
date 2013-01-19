/**
 * Created with IntelliJ IDEA.
 * User: ernesto
 * Date: 1/17/13
 * Time: 10:39 PM
 * To change this template use File | Settings | File Templates.
 */
Ext.define('App.model.patient.SOAP', {
	extend:'Ext.data.Model',
	fields:[
		{ name:'id', type:'int' },
		{ name:'pid', type:'int' },
		{ name:'eid', type:'int' },
		{ name:'uid', type:'int' },
		{ name:'date', type:'date', dateFormat:'Y-m-d H:i:s' },
		{ name:'subjective', type:'string' },
		{ name:'objective', type:'string' },
		{ name:'assessment', type:'string' },
		{ name:'plan', type:'string' },
		{ name:'icdxCodes' }
	],
	proxy:{
		type:'direct',
		api:{
			update:Encounter.updateSoapById
		}
	},
	belongsTo:{
		model:'App.model.patient.Encounter',
		foreignKey:'eid'
	}
});