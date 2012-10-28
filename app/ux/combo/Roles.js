Ext.define('App.ux.combo.Roles', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.rolescombo',
	initComponent: function() {
		var me = this;

		Ext.define('RolesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'role_name', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getRoles
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'RolesComboModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'id',
			displayField: 'role_name',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});