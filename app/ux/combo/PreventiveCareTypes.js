Ext.define('App.ux.combo.PreventiveCareTypes', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.preventivecaretypescombo',
	initComponent: function() {
		var me = this;

		Ext.define('PreventiveCareTypesModel', {
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
					list_id: 78
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'PreventiveCareTypesModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'option_value',
			displayField: 'option_name',
			emptyText   : _('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});