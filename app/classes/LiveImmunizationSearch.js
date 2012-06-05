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
Ext.define('App.classes.LiveImmunizationSearch', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.immunizationlivesearch',
	hideLabel    : true,

	initComponent: function() {
		var me = this;

		Ext.define('liveImmunizationSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id'},
				{name: 'code'},
				{name: 'code_text'},
				{name: 'code_text_short'}

			],
			proxy : {
				type  : 'direct',
				api   : {
					read: Medical.getImmunizationLiveSearch
				},
				reader: {
					totalProperty: 'totals',
					root         : 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'liveImmunizationSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(this, {
			store       : me.store,
			displayField: 'code_text_short',
			valueField  : 'code_text_short',
			emptyText   : 'Search for a Immunizations...',
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
					return '<div class="search-item"><h3>{code}<span style="font-weight: normal"> ({code_text}) </span></div>';
				}
			},
			pageSize    : 10
		}, null);

		me.callParent();
	}

});