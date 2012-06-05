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
	alias        : 'widget.medicationlivetsearch',
	hideLabel    : true,

	initComponent: function() {
		var me = this;

		Ext.define('liveMedicationsSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id'},
				{name: 'PROPRIETARYNAME'},
				{name: 'PRODUCTNDC'},
				{name: 'NONPROPRIETARYNAME'},
				{name: 'ACTIVE_NUMERATOR_STRENGTH'},
				{name: 'ACTIVE_INGRED_UNIT'}
			],
			proxy : {
				type  : 'direct',
				api   : {
					read: Medical.getMedicationLiveSearch
				},
				reader: {
					totalProperty: 'totals',
					root         : 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'liveMedicationsSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(this, {
			store       : me.store,
			displayField: 'PROPRIETARYNAME',
			valueField  : 'PROPRIETARYNAME',
			emptyText   : 'Search for a Medication...',
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
					return '<div class="search-item"><h3>{PROPRIETARYNAME}<span style="font-weight: normal"> ({NONPROPRIETARYNAME}) </span></h3>{ACTIVE_NUMERATOR_STRENGTH} | {ACTIVE_INGRED_UNIT}</div>';
				}
			},
			pageSize    : 10
		}, null);

		me.callParent();
	}

});