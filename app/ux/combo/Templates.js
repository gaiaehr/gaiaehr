Ext.define('App.ux.combo.Templates', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.mitos.templatescombo',
	initComponent: function(){
		var me = this;

		Ext.define('TemplatesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{
					name: 'id',
					type: 'int'
				},
				{
					name: 'title',
					type: 'string'
				},
				{
					name: 'body',
					type: 'string'
				}
			],
			proxy: {
				type: 'direct',
				api: {
					read: CombosData.getTemplatesTypes
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model: 'TemplatesComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			editable: false,
			displayField: 'title',
			valueField: 'id',
			emptyText: i18n('select'),
			store: me.store
		});

		me.callParent(arguments);
	}
});