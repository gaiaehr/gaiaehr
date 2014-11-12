Ext.define('App.ux.combo.Departments', {
	extend: 'Ext.form.ComboBox',
	xtype: 'depatmentscombo',
	editable: false,
	queryMode: 'local',
	valueField: 'id',
	displayField: 'title',
	emptyText: _('select'),
	store: Ext.create('App.store.administration.Departments', {
		autoLoad: true
	})
});