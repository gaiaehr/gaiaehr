Ext.define('App.ux.combo.Providers',
{
	extend : 'Ext.form.ComboBox',
	alias : 'widget.mitos.providerscombo',
	initComponent : function()
	{
		var me = this;

		Ext.define('Providersmodel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'id',
				type : 'string'
			},
			{
				name : 'name',
				type : 'string'
			}],
			proxy :
			{
				type : 'direct',
				api :
				{
					read : User.getProviders
				}
			}
		});

		me.store = Ext.create('Ext.data.Store',
		{
			model : 'Providersmodel',
			autoLoad : true
		});

		Ext.apply(this,
		{
			editable : false,
			queryMode : 'local',
			displayField : 'name',
			valueField : 'id',
			emptyText : i18n('select'),
			store : me.store
		}, null);
		me.callParent(arguments);
	}
}); 