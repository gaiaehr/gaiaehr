/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/29/11
 * Time: 4:45 PM
 */
Ext.define('App.ux.combo.posCodes', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.poscodescombo',
	initComponent: function() {
		var me = this;

		Ext.define('PosCodesModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'code', type: 'string' },
				{name: 'title', type: 'string' }
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getPosCodes
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'PosCodesModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			valueField  : 'code',
			displayField: 'title',
			emptyText   : i18n('select'),
			store       : me.store
		}, null);
		me.callParent();
	} // end initComponent
});