Ext.define('App.ux.combo.Templates', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.templatescombo',
	initComponent: function() {
		var me = this;

		Ext.define('TemplatesComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'title', type: 'string' },
				{name: 'body'}
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: CombosData.getTemplatesTypes
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'TemplatesComboModel',
			autoLoad: false
		});

		Ext.apply(this, {
			editable    : false,
			//queryMode   : 'local',
			displayField: 'title',
			valueField  : 'title',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent(arguments);
	}
});