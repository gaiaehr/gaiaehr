/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/29/11
 * Time: 4:45 PM
 */
Ext.define('App.classes.combo.Languages', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.languagescombo',
	initComponent: function() {
		var me = this;

		Ext.define('LanguagesModel', {
			extend: 'Ext.data.Model',
			fields: [
				{ name: 'lang_code', type: 'string' },
				{ name: 'lang_description', type: 'string' }
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getLanguages
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'LanguagesModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'lang_code',
			displayField: 'lang_description',
			emptyText   : 'Select',
			store       : me.store
		}, null);
		me.callParent();
	}
});