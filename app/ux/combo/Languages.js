/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/29/11
 * Time: 4:45 PM
 */
Ext.define('App.ux.combo.Languages', {
	extend: 'Ext.form.ComboBox',
	alias: 'widget.languagescombo',
	editable: false,
	valueField: 'code',
	displayField: 'description',
	emptyText: _('select'),
	initComponent: function(){
		var me = this;

		me.store = Ext.create('Ext.data.Store', {
			autoLoad: false,
			fields: [
				{name: 'code', type: 'string'},
				{name: 'description', type: 'string'}
			],
			proxy: {
				type: 'direct',
				api: {
					read: 'i18nRouter.getAvailableLanguages'
				}
			}
		});

		me.callParent();
	}
});
