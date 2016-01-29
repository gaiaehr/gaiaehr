/**
 * Created by JetBrains PhpStorm.
 * User: Ernesto J. Rodriguez (Certun)
 * File:
 * Date: 10/29/11
 * Time: 4:45 PM
 */
Ext.define('App.ux.combo.MsgNoteType', {
	extend       : 'Ext.form.ComboBox',
	alias        : 'widget.msgnotetypecombo',
	initComponent: function() {
		var me = this;

		Ext.define('MsgNoteTypeModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'option_name', type: 'string' },
				{name: 'option_value', type: 'string' }
			],
			proxy : {
				type       : 'direct',
				api        : {
					read: CombosData.getOptionsByListId
				},
				extraParams: {
					list_id: 28
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'MsgNoteTypeModel',
			autoLoad: true
		});

		Ext.apply(this, {
			editable    : false,
			queryMode   : 'local',
			displayField: 'option_name',
			valueField  : 'option_value',
			emptyText   : _('select'),
			store       : me.store
		}, null);
		me.callParent();
	} // end initComponent
});