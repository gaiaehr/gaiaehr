Ext.define('App.ux.combo.Templates', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.documentstemplatescombo',
	initComponent: function(){
		var me = this;

		Ext.define('DocumentsTemplatesComboModel', {
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
					read: 'CombosData.getTemplatesTypes'
				}
			}
		});

		Ext.apply(this, {
			editable: false,
			displayField: 'title',
			valueField: 'id',
			queryMode: 'local',
			emptyText: i18n('select'),
			store: Ext.create('Ext.data.Store', {
				model: 'DocumentsTemplatesComboModel',
				autoLoad: false
			})
		});

		me.callParent(arguments);

		me.listeners = {
			scope: me,
			beforerender:function(){
				me.getStore().load();
			}
		}
	}
});