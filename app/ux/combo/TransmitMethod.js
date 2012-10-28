Ext.define('App.ux.combo.TransmitMethod', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.transmitmethodcombo',
	initComponent: function() {
		var me = this;


		me.storeTrsmit = Ext.create('Ext.data.Store', {
			fields: ['id', 'name'],
			data  : [
				{"id": "1", "name": "Print"},
				{"id": "2", "name": "Email"},
				{"id": "3", "name": "Email"}
			]
		});

		Ext.apply(this, {
			name        : 'transmit_method',
			editable    : false,
			displayField: 'name',
			valueField  : 'id',
			queryMode   : 'local',
			emptyText   : i18n('select'),
			store       : me.storeTrsmit
		}, null);
		me.callParent();
	} // end initComponent
});