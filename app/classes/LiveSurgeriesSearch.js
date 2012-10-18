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
Ext.define('App.classes.LiveSurgeriesSearch', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.surgerieslivetsearch',
	hideLabel    : true,

	initComponent: function() {
		var me = this;

		Ext.define('liveSurgeriesSearchModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id'},
				{name: 'type'},
				{name: 'type_num'},
				{name: 'surgery'}
			],
			proxy : {
				type  : 'direct',
				api   : {
					read: Medical.getSurgeriesLiveSearch
				},
				reader: {
					totalProperty: 'totals',
					root         : 'rows'
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'liveSurgeriesSearchModel',
			pageSize: 10,
			autoLoad: false
		});

		Ext.apply(this, {
			store       : me.store,
			displayField: 'surgery',
			valueField  : 'id',
			emptyText   : i18n['search_for_a_surgery'] + '...',
			typeAhead   : true,
			minChars    : 1,
			listConfig  : {
				loadingText: i18n['searching'] + '...',
				//emptyText	: 'No matching posts found.',
				//---------------------------------------------------------------------
				// Custom rendering template for each item
				//---------------------------------------------------------------------
				getInnerTpl: function() {
					return '<div class="search-item"><h3>{surgery}<span style="font-weight: normal"> ({type}) </span></h3></div>';
				}
			},
			pageSize    : 10
		}, null);

		me.callParent();
	}

});