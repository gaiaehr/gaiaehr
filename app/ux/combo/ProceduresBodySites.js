/*
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 3/21/12
 * Time: 11:24 PM
 */
Ext.define('App.ux.combo.ProceduresBodySites', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.proceduresbodysitescombo',
	initComponent: function() {
		var me = this;

		Ext.define('ProceduresBodySitesModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name', type: 'string' },
				{name: 'option_value', type: 'string' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: CombosData.getOptionsByListId
				},
				extraParams: {
					list_id: 34
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'ProceduresBodySitesModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			displayField: 'option_name',
			valueField  : 'option_value',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});