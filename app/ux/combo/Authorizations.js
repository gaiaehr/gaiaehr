Ext.define('App.ux.combo.Authorizations',
{
	extend : 'Ext.form.ComboBox',
	alias : 'widget.mitos.authorizationscombo',
	initComponent : function()
	{
		var me = this;

		Ext.define('AuthorizationsModel',
		{
			extend : 'Ext.data.Model',
			fields : [
			{
				name : 'id',
				type : 'int'
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
					read : CombosData.getAuthorizations
				}
			}
		});

		me.store = Ext.create('Ext.data.Store',
		{
			model : 'AuthorizationsModel',
			autoLoad : true
		});

		Ext.apply(this,
		{
			editable : false,
			queryMode : 'local',
			valueField : 'id',
			displayField : 'name',
			emptyText : i18n('select'),
			store : me.store
		}, null);

		me.callParent(arguments);
	}
}); 