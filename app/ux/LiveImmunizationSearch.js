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
Ext.define('App.ux.LiveImmunizationSearch', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.immunizationlivesearch',
	hideLabel    : true,

	initComponent: function() {
		var me = this;

		Ext.define('liveImmunizationSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type:'int'},
				{name: 'cvx_code', type:'string'},
				{name: 'name', type:'string'},
				{name: 'description', type:'string'},
				{name: 'note', type:'string'},
				{name: 'update_date', type:'date', dateFormat:'Y-m-d H:i:s'}

			],
			proxy : {
				type  : 'direct',
				api   : {
					read: Immunizations.getImmunizationLiveSearch
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
			displayField: 'name',
			valueField  : 'cvx_code',
			emptyText   : i18n['search_for_a_immunizations'] + '...',
			typeAhead   : true,
			minChars    : 1,
			listConfig  : {
				loadingText: i18n['searching'] + '...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------
				getInnerTpl: function(){
					return '<div class="search-item"><h3>{cvx_code}: <span style="font-weight: normal">{name}</span></div>';
				}
			},
			pageSize    : 10
		}, null);

		me.callParent();
	}

});