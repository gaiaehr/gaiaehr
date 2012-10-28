Ext.define('App.ux.combo.CalendarCategories', {
	extend      : 'Ext.form.ComboBox',
	alias       : 'widget.mitos.calcategoriescombobox',
	editable    : false,
	displayField: 'catname',
	valueField  : 'catid',
	emptyText   : i18n('select'),

	initComponent: function() {
		var me = this;

		Ext.define('CalendarCategoriesModel', {
			extend: 'Ext.data.Model',
			fields: [
				{name: 'catid', type: 'int'},
				{name: 'catname', type: 'string'}
			],
			proxy : {
				type: 'direct',
				api : {
					read: CombosData.getCalendarCategories
				}
			}
		});

		me.store = Ext.create('Ext.data.Store', {
			model   : 'CalendarCategoriesModel',
			autoLoad: true
		});


		Ext.apply(this, {
			store: me.store
		}, null);
		me.callParent();
	}
}); 