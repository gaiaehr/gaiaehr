/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/29/11
 * Time: 4:45 PM
 */
Ext.define('App.ux.combo.TaxId', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.mitos.taxidcombo',
	initComponent: function() {
		var me = this;

		Ext.define('TaxIdsModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_id', type: 'string' },
				{name: 'title', type: 'string' }
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getTaxIds
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'TaxIdsModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			displayField: 'title',
			valueField  : 'option_id',
			emptyText   : _('select'),
			store       : me.store
		}, null);
		me.callParent();
	} // end initComponent
});