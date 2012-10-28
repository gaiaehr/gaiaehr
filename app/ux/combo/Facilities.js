Ext.define('App.ux.combo.Facilities', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.facilitiescombo',
	initComponent: function() {
		var me = this;

		Ext.define('FacilitiesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int'},
				{name: 'name', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getFacilities
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'FacilitiesComboModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'id',
			displayField: 'name',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});