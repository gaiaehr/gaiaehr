/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/29/11
 * Time: 4:45 PM
 */
Ext.define('App.ux.combo.TimeZone',
{
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.timezonecombo',
	initComponent: function() 
	{
		var me = this;

		Ext.define('TimeZoneComboModel',
		{
			extend: 'Ext.data.Model',
			fields: 
			[
				{ name: 'name', type: 'string' },
				{ name: 'value', type: 'string' }
			],
			proxy : 
			{
				type: 'direct',
				api :  
				{
					read: CombosData.getTimeZoneList
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', 
		{
			model   : 'TimeZoneComboModel',
			autoLoad: false
		});

		Ext.apply(this, 
		{
			editable    : false,
			valueField  : 'value',
			displayField: 'name',
            emptyText   : _('select'),
			store       : me.store
		}, null);
		
		me.callParent();
	}
});
