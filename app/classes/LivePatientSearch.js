/**
 * Created by JetBrains PhpStorm.
 * User: ernesto
 * Date: 6/27/11
 * Time: 8:43 AM
 * To change this template use File | Settings | File Templates.
 *
 *
 * @namespace Patient.patientLiveSearch
 */
Ext.define('App.classes.LivePatientSearch', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.patienlivetsearch',
	hideLabel    : true,

	initComponent: function() {
		var me = this;

		Ext.define('patientLiveSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'pid', type: 'int'},
				{name: 'pubpid', type: 'int'},
				{name: 'fullname', type: 'string'},
				{name: 'DOB', type: 'string'},
				{name: 'SS', type: 'string'}
			],
			proxy : {
				type  : 'direct',
				api   : {
					read: Patient.patientLiveSearch
				},
				reader: {
					totalProperty: 'totals',
					root         : 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'patientLiveSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(this, {
			store       : me.store,
			displayField: 'fullname',
			valueField  : 'pid',
			emptyText   : me.emptyText,
			typeAhead   : false,
			hideTrigger : true,
			minChars    : 1,
			listConfig  : {
				loadingText: 'Searching...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------
				getInnerTpl: function() {
					return '<div class="search-item"><h3><span>{fullname}</span>&nbsp;&nbsp;({pid})</h3>DOB:&nbsp;{DOB}&nbsp;SS:&nbsp;{SS}</div>';
				}
			},
			pageSize    : 10
		}, null);

		me.callParent();
	}

});