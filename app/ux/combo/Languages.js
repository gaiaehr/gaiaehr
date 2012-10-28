/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/29/11
 * Time: 4:45 PM
 */
Ext.define('App.ux.combo.Languages',
{
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.languagescombo',
	initComponent: function() 
	{
		var me = this;

		Ext.define('LanguagesComboModel', 
		{
			extend: 'Ext.data.Model',
			fields: 
			[
				{ name: 'code', type: 'string' },
				{ name: 'description', type: 'string' }
			],
			proxy : 
			{
				type: 'direct',
				api :  
				{
					read: i18nRouter.getAvailableLanguages
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', 
		{
			model   : 'LanguagesComboModel',
			autoLoad: false
		});

		Ext.apply(this, 
		{
			editable    : false,
			valueField  : 'code',
			displayField: 'description',
            emptyText   : i18n('select'),
			store       : me.store
		}, null);
		
		me.callParent();
	}
});
