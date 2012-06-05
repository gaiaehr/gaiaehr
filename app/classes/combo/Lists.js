Ext.define('App.classes.combo.Lists', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.listscombo',
	width        : 250,
	iconCls      : 'icoListOptions',
	initComponent: function() {
		var me = this;

		Ext.define('ListComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id'},
				{name: 'title', type: 'string' }
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getLists
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'ListComboModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'id',
			displayField: 'title',
			emptyText   : 'Select',
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});