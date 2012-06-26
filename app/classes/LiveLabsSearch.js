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
Ext.define('App.classes.LiveMedicationSearch', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.labslivetsearch',
	hideLabel    : true,

	initComponent: function() {
		var me = this;

		Ext.define('liveLabsSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id'},
				{name: 'loinc_name'}
			],
			proxy : {
				type  : 'direct',
				api   : {
					read: Medical.getLabsLiveSearch
				},
				reader: {
					totalProperty: 'totals',
					root         : 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'liveLabsSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(this, {
			store       : me.store,
			displayField: 'loinc_name',
			valueField  : 'id',
			emptyText   : 'Search...',
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
					return '<div class="search-item"><h3>{loinc_name}</h3></div>';
				}
			},
			pageSize    : 10
		}, null);

		me.callParent();
	}

});