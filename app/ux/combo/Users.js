/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/29/11
 * Time: 4:45 PM
 */
Ext.define('App.ux.combo.Users', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.userscombo',
	initComponent: function() {
		var me = this;

		Ext.define('UsersComboModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'id', type: 'int' },
				{name: 'name', type: 'string' }
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getUsers
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'UsersComboModel',
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
		me.callParent();
	} // end initComponent
});